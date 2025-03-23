# Project Title

**Creator:** Eliaz Bello Medrano

## Technologies Used

- **Front-end:** Vue.js (Version 3)
- **Back-end:** [Symfony](https://symfony.com/)
- **Database:** PostgreSQL (started with mariaDB)

## How to Use the Project

### Prerequisites
PostgreSQL: symfony focuses very hard on postgreSQL therefore a lot of features that symfony "depends" on will be way harder to use with other database server types. 
Ensure you have the following tools installed and set up on your machine:

- **DDEV**: A Docker-based development environment.
- **Docker**: Containerization platform.
- **SymfonyFlex** a package helper that installs packages that are needed for other Symfony packages. Without it you will be searching alot on fixed and working packages for simple tasks.

### Setup Instructions

1. **Start the Development Environment**:
   - Run the following command to start your development environment:

     ```bash
     ddev start
     ```

2. **Install Dependencies**:
   - If you encounter errors when accessing your website, you may need to install Composer dependencies. Run the following command in your project directory:

     ```bash
     composer install
     ```

3. **Access the Project**:
   - Once DDEV is running, you can access your Symfony application via the URL provided by DDEV (e.g., `https://your-project.ddev.site`).
   - For the Vue.js frontend, navigate to the `frontend` directory and start the Vue.js development server:

     ```bash
     cd frontend
     npm install
     npm run serve
     ```

   - Access the Vue.js application typically at `http://localhost:8080`.

### Additional Notes

- **Database Configuration**: Ensure your PostgreSQL database is configured correctly in your Symfony `.env` file.
- **Environment Variables**: Check and configure any necessary environment variables in the `.env` file for both Symfony and Vue.js.
