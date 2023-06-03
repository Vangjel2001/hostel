How To Run The Application:

To run the project locally using XAMPP, MySQL, and phpMyAdmin, you can follow these general steps:

1. Install XAMPP: Download and install XAMPP, which includes Apache web server, MySQL database server, and PHP.

2. Start Apache and MySQL: Launch the XAMPP control panel and start the Apache web server and MySQL database server.

3. Import the Database: Open phpMyAdmin by visiting http://localhost/phpmyadmin in your web browser. Create a new database for your project and run the sql code needed to create the tables.

Here is the SQL code needed to create the tables:

CREATE TABLE Room
(
  roomNumber INT NOT NULL,
  mealFee FLOAT NOT NULL,
  regularFee FLOAT NOT NULL,
  capacity INT NOT NULL,
  numberOfStudents INT NOT NULL,
  PRIMARY KEY (roomNumber)
);

CREATE TABLE Guardian
(
  guardianName VARCHAR(50) NOT NULL,
  GuardianID INT NOT NULL AUTO_INCREMENT,
  guardianSurname VARCHAR(50) NOT NULL,
  guardianPhoneNumber CHAR(10) NOT NULL,
  guardianEmail VARCHAR(50) NOT NULL,
  guardianAge INT NOT NULL,
  PRIMARY KEY (GuardianID)
);

CREATE TABLE Administrator
(
  adminName VARCHAR(30) NOT NULL,
  adminPassword VARCHAR(100) NOT NULL,
  adminEmail VARCHAR(50) NOT NULL,
  adminPhoneNumber VARCHAR(30) NOT NULL
);

CREATE TABLE Student
(
  studentID INT NOT NULL AUTO_INCREMENT,
  studentName VARCHAR(50) NOT NULL,
  studentSurname VARCHAR(50) NOT NULL,
  studentEmail VARCHAR(50) NOT NULL,
  studentPhoneNumber CHAR(10) NOT NULL,
  studentGender VARCHAR(50) NOT NULL,
  guardianRelation VARCHAR(50) NOT NULL,
  studentAge INT NOT NULL,
  education VARCHAR(50) NOT NULL,
  studentAddress VARCHAR(1000) NOT NULL,
  studentPassword VARCHAR(100) NOT NULL,
  GuardianID INT NOT NULL,
  PRIMARY KEY (studentID),
  FOREIGN KEY (GuardianID) REFERENCES Guardian(GuardianID)
);

CREATE TABLE booking
(
  duration INT NOT NULL,
  startDate DATE NOT NULL,
  endDate DATE NOT NULL,
  totalFee FLOAT NOT NULL,
  foodStatus VARCHAR(30) NOT NULL,
  studentID INT NOT NULL,
  roomNumber INT NOT NULL,
  PRIMARY KEY (studentID, roomNumber),
  FOREIGN KEY (studentID) REFERENCES Student(studentID),
  FOREIGN KEY (roomNumber) REFERENCES Room(roomNumber)
);


4. Project Setup: Place your project files inside the appropriate folder in the XAMPP htdocs directory. For example, if your project is named "hostel", you can create a folder htdocs/hostel and place your project files there.

5. Configure Database Connection: Locate the file in your project that establishes a connection to the MySQL database (e.g., pdo.php or config.php). Update the database connection details such as hostname, username, password, and database name to match your local setup.

6. Access the Project: Open your web browser and visit http://localhost/hostel (replace "hostel" with the folder name where you placed your project files). This will load the project homepage.

By following these steps, you should be able to run the project locally on your machine using XAMPP, MySQL, and phpMyAdmin.





Project Description

Worked By: Vangjel Dhima, BINF 3 B
Project Topic: Engineering a Student Hostel Management System Application
Team Leader: Vangjel Dhima
Team Members: Vangjel Dhima

Problem Statement:
Student hostels are types of residences that provide tenants with common spaces to meet up. Thus, the students can live in a single or shared room and can be able to take advantage of these common spaces to meet other students and carry out their daily activities.
 	Without a software that acts as a management system, student hostels are typically run manually. There is a lot of pressure on the person in charge of the hostel. Information regarding students, rooms etc. is not easily accessible. It is not easy to immediately find vacant rooms for new customers for example.
	Therefore, an application could really help to manage student hostels more effectively and efficiently.

Project aim:
To engineer a student hostel management web application using HTML, CSS, JavaScript, PHP and MySQL.

Application Functionalities /Project Objectives:
•	Keep track of student information
•	Keep track of student guardian information (a person like the student’s father or older brother)
•	Keep track of room booking/reservation information
•	Keep track of room information
•	Make it possible for a new customer to sign up
•	Make it possible for existing customers and the administrator to log in and change passwords
•	Make it possible for students to manage their room/rooms (view rooms and reservation details, make another reservation, cancel reservations and edit them)
•	Make it possible for students to manage their profiles (view and edit them)
•	Make it possible for the hostel administrator to manage students (view, add, edit, remove students)
•	Make it possible for the hostel administrator to manage student guardians (view, add, edit, remove student guardians)
•	Make it possible for the hostel administrator to manage rooms (view, add, edit, remove hostel rooms)
•	Make it possible for the hostel administrator to manage booking information (view, add, edit, remove hostel room reservations)
•	Make it possible for the administrator to view and change his/her username and password
•	Make the application user-friendly
•	Make the application secure, especially from HTML and SQL injections
•	Protect passwords provided by the application users
•	Fulfill all the application requirements and specifications
•	Test the application continuously
•	Evolve the application to meet possible new requirements
•	Document the whole software engineering process
	


