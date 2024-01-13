## DBMS-Pediatrics

A Database Management System Application developed for a Pediatrics Clinic. <br>
Programming Languages Used: <strong>PHP + MySQL</strong>

### Initial Setup
<ul>
  <li>Install <b>WSL</b> on your Windows System</li>
  <li>Install Ubuntu on your WSL (you can install Ubuntu from the Microsoft Store)</li>
</ul>

Open Windows Powershell on Administrator Mode and enter the following commands:
```bash
dism.exe /online /enable-feature /featurename:Microsoft-Windows-Subsystem-Linux /all /norestart
dism.exe /online /enable-feature /featurename:VirtualMachinePlatform /all /norestart
Enable-WindowsOptionalFeature -Online -FeatureName Microsoft-Windows-Subsystem-Linux
```
NOTE: After these commands is recommended to reboot your PC. <br> <br>

There is a disk image file called TDBD2_WSL.tar that contains the installation of Xampp + phpmyadmin + WordPress installation, <br> which allows to use on localhost server WordPress and phpmyadmin.
<p>To download Image follow this link: https://drive.google.com/file/d/1QzS6KLciguk0ib4wMO7e_SHYh5Gn32XF/view?usp=drive_link</p>

For Image import guide read this file located on: <strong>assets/setup.conf</strong>

### Preview of a few components

#### Records Management
![Preview - Records Management](https://github.com/andrecfoss/DBMS-Pediatrics/assets/134842813/b4de3bc3-3760-4e3c-9e30-f5956c3bfbba)

#### Units Management
![Preview - Units Management](https://github.com/andrecfoss/DBMS-Pediatrics/assets/134842813/f87daba5-44ff-45a5-82ed-dcdc4596da9a)

#### Subitems Management
![Preview - Subitems Management](https://github.com/andrecfoss/DBMS-Pediatrics/assets/134842813/c173f257-24ae-4610-9b97-3d56abbf223a)

#### Allowed Values Management
![Preview - Allowed Values Management](https://github.com/andrecfoss/DBMS-Pediatrics/assets/134842813/1255b95d-4f79-4e2b-b7b2-a729e79649c9)


<p>To clone the repository using SSH:</p>

```bash
git@github.com:andrecfoss/DBMS-Pediatrics.git
