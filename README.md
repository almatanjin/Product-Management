# Product Management System 
 
A web application built with Laravel and Vue.js to manage products, customers, and orders.
 
This project is dockerized for easy setup and deployment and are designed to be fully responsive, ensuring compatibility.
 
## Setup and Installation
 
### Prerequisites
Docker and Docker Compose installed on your machine.
 
### Steps
1. **Clone the repository**:
   ```bash
   git clone https://github.com/almatanjin/Product-Management-System.git
   cd Product-Management-System
   ```
 
2. **Setting up the Product Management System for backend**:
   - Navigate to the Laravel project directory:
     ```bash
     cd product-management-system
     ```
   - Build and start the Docker containers:
     ```bash
     ./vendor/bin/sail up
     ./vendor/bin/sail artisan migrate
     ```
   - Access the application at `http://localhost:8000`

3. **Setting up the Product Management System for frontend**:
   - Navigate to the Laravel project directory:
     ```bash
     cd frontend
     cd product-management-system
     ```
   - Build and start the Docker containers:
     ```bash
     docker build -t dockerize-vuejs .
     docker run -it -p 4000:4000 --rm --name dockerize-vuejs dockerize-vuejs
     ```
   - Access the application at `http://localhost:4000`
 
## Usage
 
- **Managing Products, Customers, and Orders**: After logging in, you can manage products, customers, and orders through the dashboard.
- **Import/Export Data**: Use the CSV import/export feature to manage data in bulk.
- - Product Csv must contain the following columns [Name, Description, Price]
- - Customer Csv must contain the following columns [First Name, Last Name, Email, Phone, Address]
- - Order Csv must contain the following columns [Customer Id, total_amount, status]
## Contributing
 
Contributions are welcome! Please submit a pull request or open an issue for any suggestions or bug reports.
 
## License
 
This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.
 
---
