# Component Architecture & Template Hierarchy

```
app/
├── controllers/
│   ├── HomeController.php
│   ├── SafariController.php
│   ├── JourneyController.php
│   ├── EnquiryController.php
│   ├── JournalController.php
│   └── Admin/
│       ├── DashboardController.php
│       ├── CrmController.php
│       ├── CmsController.php
│       └── MediaController.php
├── models/
│   ├── Database.php
│   ├── Enquiry.php
│   ├── Safari.php
│   ├── Post.php
│   └── User.php
└── views/
    ├── layouts/
    │   ├── main.php
    │   └── admin.php
    ├── partials/
    │   ├── header.php
    │   ├── footer.php
    │   ├── mobile_dock.php
    │   └── inquiry_form.php
    └── pages/
        ├── home.php
        ├── safari_detail.php
        ├── destination.php
        ├── journal_detail.php
        └── plan_journey.php
```
