## SimpEduK-Desa: A Streamlined Village Management System
# Implemented at Warung Bambu village, Karawang, West Java, Indonesia

SimpEduK-Desa is a web-based application crafted to simplify financial management in Indonesian villages. Built with the robust CodeIgniter 3 framework, it provides a user-friendly interface for managing village finances transparently and efficiently.

**Key Features**

* **APBD (Village Budget) Management (soon):** 
    * Easily plan and input your village's annual budget (APBDes).
    * Track budget allocations across different sectors and programs.
    * Monitor budget realization and identify potential variances.
* **Financial Transaction Recording (soon):**
    * Record all income and expenses with clear categorization.
    * Attach supporting documents (scanned receipts, invoices) for transparency.
    * Generate detailed financial reports for analysis and auditing.
* **User Roles & Permissions:**
    * Assign specific roles (e.g., Admin, Operator, Viewer) to different users.
    * Control access to sensitive financial data based on user roles.
* **Villager Data Administration:**
    * Create new villager data (birth)
    * Create death report for villager who's passed away
    * Create villager report for move out from the village
* **Print-Ready Reports:**
    * Generate various financial reports in printable formats (PDF, Excel).
    * Easily share reports with relevant stakeholders for transparency and accountability. 

**Who is SimpEduK-Desa for?**

* **Village Officials:** Streamline financial management, improve transparency, and enhance accountability within the village administration.
* **Village Communities:** Access up-to-date financial information, promoting trust and participation in village development.

**Understanding the Code (For Developers)**

SimpEduK-Desa follows the Model-View-Controller (MVC) architectural pattern, making the code organized and maintainable:

* **Models:** Interact with the database to handle data storage and retrieval.
* **Views:**  Display information to the user and handle user interactions.
* **Controllers:** Process user requests, interact with models, and load appropriate views.

**Getting Started (Installation & Setup)**

1. **Download the source code:** Get the latest version from the GitHub repository: [https://github.com/nrzngr/simpeduk-desa](https://github.com/nrzngr/simpeduk-desa)
2. **Set up your database:** Create a MySQL database and import the provided database schema (`simpeduk_desa.sql`).
3. **Configure database connection:** Update the database configuration settings in the `application/config/database.php` file.
4. **Set base URL:** Configure your application's base URL in the `application/config/config.php` file. 
5. **Access SimpEduK-Desa:**  Open your web browser and navigate to your application's URL. 

**Potential Enhancements**

* **Multi-Year Budget Planning:** Allow for planning and tracking budgets across multiple fiscal years.
* **Data Visualization:**  Incorporate charts and graphs to visualize financial data for easier analysis.
* **Mobile-Friendly Interface:** Optimize the user interface for access on mobile devices.
* **Integration with External Systems:** Explore integrations with banking APIs for automated transaction processing.

**Conclusion**

SimpEduK-Desa is a valuable tool for improving financial management practices in Indonesian villages. Its user-friendly interface, comprehensive features, and focus on transparency make it a practical solution for efficient and accountable financial administration.
