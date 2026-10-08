<?php
require_once '../header.php';
?>
<section style="padding: 6rem 0; background-color: var(--color-bg-base);">
    <div class="container" style="max-width: 800px; text-align: center;">
        <h1 class="text-display-1" style="margin-bottom: 1rem;">Plan Your Journey</h1>
        <p style="margin-bottom: 3rem;">Begin the conversation with our expedition specialists to craft your bespoke Jawai experience.</p>
        
        <div style="background: var(--color-bg-surface); padding: 3rem; border: 1px solid var(--color-border-subtle); border-radius: 8px; box-shadow: var(--shadow-card); text-align: left; position: relative;">
            
            <div id="step-indicator" style="display: flex; justify-content: space-between; margin-bottom: 2rem; font-family: 'Space Mono', monospace; font-size: 0.8rem; color: var(--color-text-muted);">
                <span class="active-step" style="color: var(--color-accent-amber);">1. Contact Details</span>
                <span>2. Expedition Preferences</span>
                <span>3. Additional Notes</span>
            </div>

            <form id="inquiry-form" action="/api/enquiries" method="POST">
                
                <!-- Step 1: Contact -->
                <div class="form-step active" id="step-1">
                    <h3 class="text-display-3" style="margin-bottom: 1.5rem;">Who is traveling?</h3>
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; font-weight: 500; margin-bottom: 0.5rem;">Full Name *</label>
                        <input type="text" name="full_name" required style="width: 100%; height: 52px; padding: 0 1rem; border: 1px solid var(--color-border-medium); border-radius: 4px; font-family: inherit;">
                    </div>
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; font-weight: 500; margin-bottom: 0.5rem;">Email Address *</label>
                        <input type="email" name="email" required style="width: 100%; height: 52px; padding: 0 1rem; border: 1px solid var(--color-border-medium); border-radius: 4px; font-family: inherit;">
                    </div>
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; font-weight: 500; margin-bottom: 0.5rem;">Phone Number *</label>
                        <input type="tel" name="phone" required style="width: 100%; height: 52px; padding: 0 1rem; border: 1px solid var(--color-border-medium); border-radius: 4px; font-family: inherit;">
                    </div>
                    <button type="button" class="btn-primary next-btn" style="width: 100%; border: none; cursor: pointer; font-size: 1.125rem;">Next Step &rarr;</button>
                </div>

                <!-- Step 2: Details -->
                <div class="form-step" id="step-2" style="display: none;">
                    <h3 class="text-display-3" style="margin-bottom: 1.5rem;">Expedition Details</h3>
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; font-weight: 500; margin-bottom: 0.5rem;">Travel Dates (Approximate)</label>
                        <input type="text" name="travel_dates" placeholder="e.g. Mid October 2026" style="width: 100%; height: 52px; padding: 0 1rem; border: 1px solid var(--color-border-medium); border-radius: 4px; font-family: inherit;">
                    </div>
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; font-weight: 500; margin-bottom: 0.5rem;">Number of Guests</label>
                        <div style="display: flex; gap: 1rem;">
                            <input type="number" name="adults_count" placeholder="Adults" min="1" value="2" required style="width: 50%; height: 52px; padding: 0 1rem; border: 1px solid var(--color-border-medium); border-radius: 4px; font-family: inherit;">
                            <input type="number" name="children_count" placeholder="Children" min="0" value="0" required style="width: 50%; height: 52px; padding: 0 1rem; border: 1px solid var(--color-border-medium); border-radius: 4px; font-family: inherit;">
                        </div>
                    </div>
                    <div style="display: flex; gap: 1rem;">
                        <button type="button" class="btn-ghost prev-btn" style="width: 50%; cursor: pointer;">&larr; Back</button>
                        <button type="button" class="btn-primary next-btn" style="width: 50%; border: none; cursor: pointer; font-size: 1.125rem;">Next Step &rarr;</button>
                    </div>
                </div>

                <!-- Step 3: Notes -->
                <div class="form-step" id="step-3" style="display: none;">
                    <h3 class="text-display-3" style="margin-bottom: 1.5rem;">Additional Information</h3>
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; font-weight: 500; margin-bottom: 0.5rem;">Special Interests or Requests</label>
                        <textarea name="notes" rows="5" placeholder="Tell us about what you hope to experience..." style="width: 100%; padding: 1rem; border: 1px solid var(--color-border-medium); border-radius: 4px; font-family: inherit; resize: vertical;"></textarea>
                    </div>
                    <div style="display: flex; gap: 1rem;">
                        <button type="button" class="btn-ghost prev-btn" style="width: 50%; cursor: pointer;">&larr; Back</button>
                        <button type="submit" class="btn-primary" style="width: 50%; border: none; cursor: pointer; font-size: 1.125rem;">Submit Inquiry</button>
                    </div>
                </div>

                <div id="form-message" style="display: none; margin-top: 1.5rem; padding: 1rem; border-radius: 4px; text-align: center;"></div>
            </form>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const steps = document.querySelectorAll('.form-step');
    const indicators = document.querySelectorAll('#step-indicator span');
    const nextBtns = document.querySelectorAll('.next-btn');
    const prevBtns = document.querySelectorAll('.prev-btn');
    const form = document.getElementById('inquiry-form');
    const msgDiv = document.getElementById('form-message');

    let currentStep = 0;

    const updateUI = () => {
        steps.forEach((step, index) => {
            step.style.display = index === currentStep ? 'block' : 'none';
        });
        indicators.forEach((ind, index) => {
            ind.style.color = index === currentStep ? 'var(--color-accent-amber)' : 'var(--color-text-muted)';
        });
    };

    nextBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            // Basic validation for the current step
            const inputs = steps[currentStep].querySelectorAll('input[required]');
            let valid = true;
            inputs.forEach(input => {
                if (!input.checkValidity()) {
                    input.reportValidity();
                    valid = false;
                }
            });
            if (valid && currentStep < steps.length - 1) {
                currentStep++;
                updateUI();
            }
        });
    });

    prevBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            if (currentStep > 0) {
                currentStep--;
                updateUI();
            }
        });
    });

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.textContent = 'Submitting...';
        submitBtn.disabled = true;

        const formData = new FormData(form);
        try {
            const response = await fetch('/api/enquiries/index.php', { // Note the path
                method: 'POST',
                body: formData
            });
            const result = await response.json();
            
            msgDiv.style.display = 'block';
            if (response.ok) {
                msgDiv.style.backgroundColor = '#e8f5e9';
                msgDiv.style.color = '#2e7d32';
                msgDiv.textContent = result.message || 'Inquiry submitted successfully.';
                steps[currentStep].style.display = 'none'; // hide form
                document.getElementById('step-indicator').style.display = 'none';
            } else {
                msgDiv.style.backgroundColor = '#ffebee';
                msgDiv.style.color = '#c62828';
                msgDiv.textContent = result.error || 'An error occurred.';
                submitBtn.textContent = 'Submit Inquiry';
                submitBtn.disabled = false;
            }
        } catch (error) {
            msgDiv.style.display = 'block';
            msgDiv.style.backgroundColor = '#ffebee';
            msgDiv.style.color = '#c62828';
            msgDiv.textContent = 'A network error occurred. Please try again.';
            submitBtn.textContent = 'Submit Inquiry';
            submitBtn.disabled = false;
        }
    });
});
</script>

<?php
require_once '../footer.php';
?>
