
#SWIBL-Registration Service


This service API addresses the functionality required to support the registration process.    This will support the ability to create, update, delete and retrieve registration data.  

##Configuration File (config.ini)
The service requires a configuraiton file to be placed in the root path of the service.  The filename is "config.ini".  The following is the structure that is required.

[database]<br/>
driver = "MySQL"<br/>
host = ""<br/>
database = ""<br/>
user = ""<br/>
password = ""<br/>
<br/>
[log]<br/>
log.enabled=1<br/>
log.file="RegistrationService.log"<br/>
log.level=1<br/>
<br/>
[authentication]<br/>
authentication.enabled = 0<br/>