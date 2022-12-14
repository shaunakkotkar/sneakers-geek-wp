# sneakers-geek-wp
Created VPC in Mumbai region with 2 public and 2 private subnets

Launched jump host instance on the public subnet and main app instance on private subnet.

In main app instance installed apache, MySQL client, PHP. because the app is on PHP WordPress

Code is cloned in /var/www/html directory and changes the path with the directory name in the apache config file (/etc/apace2/sites-available/000-default.conf)

Created a VPC in another region(us-west-1 N. Calfornia) and created RDS (mysql) in it.

Also created VPC peering in both VPC’s in the same account with different regions (ap-south-1 and us-west-2) also add All ICMP port in both security group  



In vpc peering enabled DNS setting on both regions (Allow accepter VPC to resolve DNS of hosts in requester VPC to private IP addresses)

Added both VPC IPv4 CIDR IP in both vpc’s route tables and connected both 

Connected database with main app instance which is in another vpc(Mumbai region)



Created a new target group with main app instance and application load balancer in Mumbai region vpc, added both public subnets in it and host a site on it  
  

  

 





