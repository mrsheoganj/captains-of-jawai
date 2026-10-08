<?php require_once '../header.php'; ?>

<!-- Page Banner -->
<div class="page-banner" style="background-image: url('/assets/images/landscape1.jpg'); background-size: cover; background-position: center; padding: 120px 0; text-align: center; color: white; position: relative;">
    <div style="position: absolute; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.5);"></div>
    <div class="container" style="position: relative; z-index: 2;">
        <h1 style="font-size: 50px; text-shadow: 0 4px 10px rgba(0,0,0,0.3); color: #fff;">Plan Your Journey</h1>
        <p style="font-size: 18px;">Begin the conversation with our expedition specialists to craft your bespoke Jawai experience.</p>
    </div>
</div>

<div class="section-padding bg-light">
    <div class="container" style="max-width: 800px;">
        <div style="background: #fff; padding: 40px; border-radius: 16px; box-shadow: var(--shadow-soft);">
            
            <div id="step-indicator" style="display: flex; justify-content: space-between; margin-bottom: 30px; font-weight: bold; font-size: 14px; color: #9ca3af; border-bottom: 2px solid #f3f4f6; padding-bottom: 15px;">
                <span class="active-step" style="color: var(--primary-color);">1. Contact Details</span>
                <span>2. Expedition Preferences</span>
                <span>3. Additional Notes</span>
            </div>

            <form id="inquiry-form" action="/api/enquiries/index.php" method="POST">
                
                <!-- Step 1: Contact -->
                <div class="form-step active" id="step-1">
                    <h3 style="margin-bottom: 25px; color: var(--text-dark);">Who is traveling?</h3>
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-weight: bold; margin-bottom: 8px;">Full Name *</label>
                        <input type="text" name="full_name" required style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 8px;">
                    </div>
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-weight: bold; margin-bottom: 8px;">Email Address *</label>
                        <input type="email" name="email" required style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 8px;">
                    </div>
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-weight: bold; margin-bottom: 8px;">Phone Number *</label>
                        <input type="tel" name="phone" required style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 8px;">
                    </div>
                    <button type="button" class="primary-btn1 next-btn" style="width: 100%; border: none; cursor: pointer;">Next Step &rarr;</button>
                </div>

                <!-- Step 2: Details -->
                <div class="form-step" id="step-2" style="display: none;">
                    <h3 style="margin-bottom: 25px; color: var(--text-dark);">Expedition Details</h3>
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-weight: bold; margin-bottom: 8px;">Travel Dates (Approximate)</label>
                        <input type="text" name="travel_dates" placeholder="e.g. Mid October 2026" style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 8px;">
                    </div>
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-weight: bold; margin-bottom: 8px;">Number of Guests</label>
                        <div style="display: flex; gap: 15px;">
                            <input type="number" name="adults_count" placeholder="Adults" min="1" value="2" required style="width: 50%; padding: 12px; border: 1px solid #ccc; border-radius: 8px;">
                            <input type="number" name="children_count" placeholder="Children" min="0" value="0" required style="width: 50%; padding: 12px; border: 1px solid #ccc; border-radius: 8px;">
                        </div>
                    </div>
                    <div style="display: flex; gap: 15px;">
                        <button type="button" class="primary-btn1 prev-btn" style="width: 50%; background: #9ca3af; border-radius: 8px;">&larr; Back</button>
                        <button type="button" class="primary-btn1 next-btn" style="width: 50%; border: none; cursor: pointer;">Next Step &rarr;</button>
                    </div>
                </div>

                <!-- Step 3: Notes -->
                <div class="form-step" id="step-3" style="display: none;">
                    <h3 style="margin-bottom: 25px; color: var(--text-dark);">Additional Information</h3>
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-weight: bold; margin-bottom: 8px;">Special Interests or Requests</label>
                        <textarea name="notes" rows="5" placeholder="Tell us about what you hope to experience..." style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 8px; resize: vertical;"></textarea>
                    </div>
                    <div style="display: flex; gap: 15px;">
                        <button type="button" class="primary-btn1 prev-btn" style="width: 50%; background: #9ca3af; border-radius: 8px;">&larr; Back</button>
                        <button type="submit" class="primary-btn1" style="width: 50%; border: none; cursor: pointer;">Submit Enquiry</button>
                    </div>
                </div>

                <div id="form-message" style="display: none; margin-top: 20px; padding: 15px; border-radius: 8px; text-align: center; font-weight: bold;"></div>
            </form>
        </div>
    </div>
</div>

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
            ind.style.color = index === currentStep ? 'var(--primary-color)' : '#9ca3af';
        });
    };

    nextBtns.forEach(btn => {
        btn.addEventListener('click', () => {
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
            const response = await fetch('/api/enquiries/index.php', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();
            
            msgDiv.style.display = 'block';
            if (response.ok) {
                msgDiv.style.backgroundColor = '#dcfce7';
                msgDiv.style.color = '#166534';
                msgDiv.textContent = result.message || 'Inquiry submitted successfully. We will contact you soon!';
                steps[currentStep].style.display = 'none';
                document.getElementById('step-indicator').style.display = 'none';
            } else {
                msgDiv.style.backgroundColor = '#fee2e2';
                msgDiv.style.color = '#991b1b';
                msgDiv.textContent = result.error || 'An error occurred.';
                submitBtn.textContent = 'Submit Enquiry';
                submitBtn.disabled = false;
            }
        } catch (error) {
            msgDiv.style.display = 'block';
            msgDiv.style.backgroundColor = '#fee2e2';
            msgDiv.style.color = '#991b1b';
            msgDiv.textContent = 'A network error occurred. Please try again.';
            submitBtn.textContent = 'Submit Enquiry';
            submitBtn.disabled = false;
        }
    });
});
</script>

<?php require_once '../footer.php'; ?>
