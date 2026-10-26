# Scraper module

This module is the home for external property data collection and import workflows.

Keep source-specific fetching, parsing, and normalization in this module. Persist imported data through the relevant application services or repositories. Add HTTP endpoints only when an authenticated operational interface is defined; scheduled or queued execution is preferable for long-running collection jobs.

Routes are loaded with the other modules by `ModuleServiceProvider` and belong in `Routes/web.php`.
