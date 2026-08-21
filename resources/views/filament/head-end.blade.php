<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<style>
    /* Səhifə, form və cədvəllər üçün yan (sağ/sol) boşluqların yığcam və optimal edilməsi */
    .fi-main-ctn, .fi-top-nav-ctn {
        max-width: 97% !important;
        margin-left: auto !important;
        margin-right: auto !important;
        padding-left: 1rem !important;
        padding-right: 1rem !important;
    }
    .fi-page {
        padding-left: 0.25rem !important;
        padding-right: 0.25rem !important;
    }
    .fi-ta-ctn {
        border-radius: 14px !important;
    }
    /* Grid rejimində tək vahid, səliqəli və kənarsız kart forması */
    .fi-ta-content.grid,
    .fi-ta-records.grid {
        gap: 1.25rem !important;
    }
    .fi-ta-content.grid .fi-ta-record,
    .fi-ta-records.grid .fi-ta-record {
        padding: 0 !important;
        border-radius: 16px !important;
        overflow: hidden !important;
        background: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05) !important;
        transition: transform 0.25s ease, box-shadow 0.25s ease !important;
    }
    .fi-ta-content.grid .fi-ta-record:hover,
    .fi-ta-records.grid .fi-ta-record:hover {
        transform: translateY(-4px) !important;
        box-shadow: 0 14px 28px rgba(0, 0, 0, 0.1) !important;
    }
    .fi-ta-content.grid .fi-ta-record > div,
    .fi-ta-content.grid .fi-ta-cell,
    .fi-ta-content.grid .fi-ta-col-wrp,
    .fi-ta-records.grid .fi-ta-cell,
    .fi-ta-records.grid .fi-ta-col-wrp {
        padding: 0 !important;
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
    }
    /* Səhifələmədəki say seçimini (per-page select) tamamilə gizlətmək və səhifələmə düymələrini sağa çəkmək */
    .fi-ta-pagination-records-per-page-select-ctn,
    .fi-ta-pagination select,
    .fi-pagination-records-per-page-select-ctn,
    .fi-ta-pagination label:has(select) {
        display: none !important;
    }
    .fi-ta-pagination {
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
    }
    .fi-ta-pagination nav,
    .fi-pagination {
        margin-left: auto !important;
    }
</style>
