# Captains of Jawai — Official Documentation Repository

**Domain:** https://captainsofjawai.com/  
**Master Asset:** `assets/logo.PNG`  
**Target Environment:** GoDaddy Linux cPanel, PHP 8.3, MariaDB, `/public_html`  

---

## Overview
This repository contains the complete research, brand strategy, design system, UX architecture, SEO strategy, content library, CRM specifications, and technical implementation blueprints for **Captains of Jawai**.

## Repository Directory Structure
```
/
├── assets/
│   └── logo.PNG                 <-- Official Brand Logo Asset
├── docs/
│   ├── PROJECT_MASTER.md        <-- Master Single Source of Truth
│   ├── RESEARCH_EXECUTIVE_SUMMARY.md
│   ├── FINAL_RECOMMENDATION.md
│   ├── CLIENT_INPUT_REQUIRED.md
│   ├── RISKS_AND_OPEN_QUESTIONS.md
│   ├── research/                <-- Deep regional & wildlife research
│   ├── competitors/             <-- Comprehensive competitor landscape
│   ├── brand/                   <-- Positioning, voice, messaging
│   ├── audience/                <-- Personas & customer journeys
│   ├── ux/                      <-- Sitemap, homepage & page specs
│   ├── design/                  <-- Colors, typography, design system
│   ├── content/                 <-- CMS model, copy & 50 journal topics
│   ├── seo/                     <-- Keywords, schema, technical SEO
│   ├── admin/                   <-- Dashboard, CRM & media specs
│   ├── technical/               <-- Tech stack, DB schema, API, deploy
│   ├── antigravity/             <-- Build prompt & implementation guide
│   └── assets/                  <-- Asset inventory & image rights
└── README.md                    <-- This file
```

## How to Build with Antigravity IDE / AI Coding Agent
1. Review `docs/PROJECT_MASTER.md`.
2. Supply `docs/antigravity/MASTER_BUILD_PROMPT.md` to Antigravity IDE or your coding agent.
3. Follow the build sequence in `docs/antigravity/build-order.md`.

## Implementation status
The blueprint in this folder has been implemented in `/public_html` + `/private` (see the root [`README.md`](../README.md) for features and the GoDaddy deployment guide):
- Public site per `docs/ux/sitemap.md` and `docs/ux/homepage.md` (10 homepage sections, multi-step enquiry wizard, mobile sticky dock).
- Design tokens from `docs/design/colors.md` / `typography.md` (Cormorant Garamond, Plus Jakarta Sans, Space Mono; light editorial theme).
- Admin suite per `docs/admin/*`: dashboard KPIs, CRM pipeline, CMS, media vault, SEO module (meta, redirects, robots, sitemap, audit), settings (incl. SMTP & email routing), RBAC users and audit log.
- Technical/SEO per `docs/technical/*` and `docs/seo/*`: PHP 8.3 + MariaDB, PDO, CSRF, Argon2id, JSON-LD schema, canonical URLs.
- Items in `docs/CLIENT_INPUT_REQUIRED.md` are left as editable settings — nothing is fabricated.
