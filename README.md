# Check-LocalAdminHash
Check-LocalAdminHash is a PowerShell tool that attempts to authenticate to multiple hosts over either WMI or SMB using a password hash to determine if the provided credential is a local administrator. It's useful if you obtain a password hash for a user and want to see where they are local admin on a network. It is essentially a Frankenstein of two of my favorite tools along with some of my own code. It utilizes Kevin Robertson's (@kevin_robertson) Invoke-TheHash project for the credential checking portion. Additionally, the script utilizes modules from PowerView by Will Schroeder (@harmj0y) and Matt Graeber (@mattifestation) to enumerate domain computers to find targets for testing admin access against. 

![alt text](http://www.dafthack.com/Check-LocalAdminHash-Example.jpg)

## Examples


### Scanning All Hosts Over WMI
This command will use the domain 'testdomain.local' to lookup all systems and then attempt to authenticate to each one using the user 'testdomain.local\PossibleAdminUser' and a password hash over WMI.
```PowerShell
Check-LocalAdminHash -Domain testdomain.local -UserDomain testdomain.local -Username PossibleAdminUser -PasswordHash E62830DAED8DBEA4ACD0B99D682946BB -AllSystems
```


### Using A CIDR Range
This command will use the provided CIDR range to generate a target list and then attempt to authenticate to each one using the local user 'PossibleAdminUser' and a password hash over WMI.
```PowerShell
Check-LocalAdminHash -Username PossibleAdminUser -PasswordHash E62830DAED8DBEA4ACD0B99D682946BB -CIDR 192.168.1.0/24
```


### Using Target List and SMB and Output to File
This command will use the provided targetlist and attempt to authenticate to each host using the local user 'PossibleAdminUser' and a password hash over SMB.
```PowerShell
Check-LocalAdminHash -Username PossibleAdminUser -PasswordHash E62830DAED8DBEA4ACD0B99D682946BB -TargetList C:\temp\targetlist.txt -Protocol SMB | Out-File -Encoding Ascii C:\temp\local-admin-systems.txt
```


### Single Target
This command attempts to perform a local authentication for the user Administrator against the system 192.168.0.16 over SMB.
```PowerShell
Check-LocalAdminHash -TargetSystem 192.168.0.16 -Username Administrato -PasswordHash E62830DAED8DBEA4ACD0B99D682946BB -Protocol SMB
```

### Check-LocalAdminHash Options
```
Username - The Username for attempting authentication.
PasswordHash - Password hash of the user.
TargetSystem - Single hostname or IP for authentication attempt.
TargetList - A list of hosts to scan one per line
AllSystems - A switch that when enabled utilizes PowerView modules to enumerate all domain systems. This list is then used to check local admin access.
Domain - This is the domain that PowerView will utilize for discovering systems.
UserDomain - This is the user's domain to authenticate to each system with. Don't use this flag if using a local cred instead of domain cred.
Protocol - This is the setting for whether to check the hash using WMI or SMB. Default is 'WMI' but set it to 'SMB' to check that instead.
CIDR - Specify a CIDR form network range such as 192.168.0.0/24
Threads - Defaults to 5 threads. (I've run into some odd issues setting threads more than 15 with some results not coming back.)
```

## Credits
Check-LocalAdminHash is pretty much a Frankenstein of two of my favorite tools, PowerView and Invoke-TheHash. 95% of the code is from those two tools. So the credit goes to Kevin Robertson (@kevin_robertson) for Invoke-TheHash, and credit goes to Will Schroeder (@harmj0y), Matt Graeber (@mattifestation) (and anyone else who worked on PowerView). Without those two tools this script wouldn't exist. Also shoutout to Steve Borosh (@424f424f) for help with the threading and just being an all around awesome dude.

Invoke-TheHash - https://github.com/Kevin-Robertson/Invoke-TheHash

PowerView - https://raw.githubusercontent.com/PowerShellMafia/PowerSploit/dev/Recon/PowerView.ps1
