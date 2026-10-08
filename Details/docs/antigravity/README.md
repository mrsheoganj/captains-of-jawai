# Antigravity IDE / AI Agent Implementation Blueprint

This directory contains the precise, step-by-step engineering directives for an AI coding agent or Antigravity IDE to construct, test, and deploy **Captains of Jawai** (https://captainsofjawai.com/).

## Core Preconditions for the AI Builder
1. **No Direct Online Booking:** Do NOT build an e-commerce cart, automated checkout, or instant booking engine. The platform is an **inquiry-led luxury consultation engine**.
2. **Preserve Master Brand Logo:** The brand logo is `assets/logo.PNG` (and `docs/assets/logo.PNG`). Do NOT replace it with an AI-generated SVG or fictional logo.
3. **No Fabricated Data:** Where client parameters are missing (exact phone number, GSTIN, legal address, specific package prices), use standardized `CLIENT INPUT REQUIRED` tokens from `docs/CLIENT_INPUT_REQUIRED.md`.
4. **Native GoDaddy cPanel Deployment:** Target PHP 8.3, Apache (`.htaccess`), and MariaDB/MySQL. Zero Node.js runtime daemon dependencies.
