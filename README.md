<img width="2478" height="549" alt="Temperature and Humidity Monitoring" src="https://github.com/user-attachments/assets/fe839e82-9044-48da-a3d3-5ff34996869d" />

## 📃 Description
Temperature and Humidity Monitoring is a project designed to monitor environmental temperature and humidity and store the collected data into a database. The system is divided into two main parts: the IoT system, which is responsible for reading and sending sensor data, and the backend system, which processes the data and inserts it into the database. For data visualization, this project relies on the Node-RED Dashboard.

This project is my first project in learning and implementing Object-Oriented Programming (OOP) using PHP. Through this project, I aim to combine PHP OOP concepts with IoT systems to build a simple monitoring system that serves as a practical learning experience and helps deepen my understanding of backend development and IoT integration.

## ⚙️ Tech Stack
![Static Badge](https://img.shields.io/badge/PHP-%23777BB3?style=for-the-badge&logo=php&logoColor=white&logoSize=auto) ![Static Badge](https://img.shields.io/badge/MQTT-%23660066?style=for-the-badge&logo=mqtt&logoColor=white&logoSize=auto) ![Static Badge](https://img.shields.io/badge/Node--RED-%238F0000?style=for-the-badge&logo=nodered&logoColor=white&logoSize=auto) ![Static Badge](https://img.shields.io/badge/Eclipse_Mosquitto-%233C5280?style=for-the-badge&logo=eclipsemosquitto&logoColor=white&logoSize=auto)

This project is built using native PHP with Object-Oriented Programming (OOP) principles to handle backend logic, data processing, and database interactions. The OOP approach is applied to create a structured, modular, and maintainable codebase while serving as a learning foundation for PHP OOP implementation.

For data visualization, Node-RED is used to display temperature and humidity monitoring results through the Node-RED Dashboard module, providing a simple and interactive real-time interface. Communication between the IoT system and the backend is handled using the MQTT protocol, with Mosquitto acting as the MQTT broker to ensure lightweight, reliable, and efficient message delivery.

## 🗝️ Features
- **Temperature and Humidity Data Storage:** The system receives temperature and humidity data from the IoT devices and stores the validated data into the database for further processing and analysis.

- **Two-Stage Data Filtering and Validation:** Data validation is performed in two stages: first when the message is received from the MQTT broker, and second before the data is inserted into the database. This approach helps ensure data integrity and prevents invalid or malformed data from being stored.

- **System Activity Logging:** The system implements a logging mechanism to record system activities and events, allowing developers to track data flow, monitor errors, and review the system’s activity history for debugging and maintenance purposes.

## 📷 Project Demo
To see documentation of this project, you can click [this](https://drive.google.com/drive/folders/19ztHTA7iOQJ93ZhrLr7cAxauaWqNFjPS?usp=drive_link) Sorry for the poor resolution of my cam, I tried my best to get the best visual. If you have any question or you want to see more, just feel free to ask.

## 👥 Contributors
<a href="https://github.com/call-me-ahmaaad/temp-humid-monitoring/graphs/contributors">
  <img src="https://contrib.rocks/image?repo=call-me-ahmaaad/temp-humid-monitoring" />
</a>

Made with [contrib.rocks](https://contrib.rocks).
