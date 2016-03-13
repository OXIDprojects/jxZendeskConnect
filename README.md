# jxZendeskConnect #

OXID eShop Admin Extension for Connecting with Zendesk Service


## OXID Setup ##

For installing and using the module, you have to proceed the following steps.

1. Unzip the complete file with all the folder structures and upload the content of the folder copy_this to the root folder of your shop.
2. After this navigate in the admin backend of the shop to _Extensions_ - _Modules_. Select the module _jxZendeskConnect_ and click on `Activate`.
4. Switch to the tab _Settings_ and setup the fields
    * Zendesk Server Url eg. https://myserver.zendesk.com
    * User and Toekn, eg. ZendeskUser / mytolen
    * Values for project and author
    * Custom Field IDs (from Zendesk) for customer (name and) e-mail and the order number

## Zendesk Setup ##

The tickets created by the module _jxZendeskConnect_ have to have attributes which allows to assign these tickets to customers. Therefore two additional custom fields have to be defined in Zendesk.

## Screenshots ##

#### Settings ####
![Object History Log](https://github.com/job963/jxZendeskConnect/raw/master/docs/img/settings-de.png)

#### Issue Info for each User ####
![Full Log Report](https://github.com/job963/jxZendeskConnect/raw/master/docs/img/user-main-de.png)

#### Issues of a User ####
![Full Log Report](https://github.com/job963/jxZendeskConnect/raw/master/docs/img/user-jiraissues-de.png)

#### Issue Info for each Order/User ####
![Full Log Report](https://github.com/job963/jxZendeskConnect/raw/master/docs/img/order-main-de.png)

#### Issues of a User/Order ####
![Full Log Report](https://github.com/job963/jxZendeskConnect/raw/master/docs/img/user-jiraissues-de.png)

#### All open Issues ####
![Full Log Report](https://github.com/job963/jxZendeskConnect/raw/master/docs/img/user-allissues-de.png)


