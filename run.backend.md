### Run Eats Backend.

```bash

composer install --optimize-autoloader --ignore-platform-reqs
takeown /f D:\EatsProject\backend\storage /a
takeown /f D:\EatsProject\backend\bootstrap /a
takeown /f D:\EatsProject\backend\vendor /a
takeown /f D:\EatsProject\backend\storage\framework /a
takeown /f D:\EatsProject\backend\bootstrap\cache /a
mkdir -p bootstrap/cache/
mkdir -p storage/framework/sessions/
mkdir -p storage/framework/views/
mkdir -p storage/framework/cache/
mkdir bootstrap/cache/
mkdir storage/framework/sessions/
mkdir storage/framework/views/
mkdir storage/framework/cache/

chown 777 bootstrap/cache/
chown 777 storage/framework/sessions/
chown 777 storage/framework/views/
chown 777 storage/framework/cache/
php artisan key:generate
php artisan storage:link
php artisan cache:clear
php artisan config:clear
php artisan view:clear
composer install --optimize-autoloader --ignore-platform-reqs
npm i
npm audit fix
php artisan migrate
php artisan serve

```


```http


https://firebasemessagingcampaigns.clients6.google.com/v1beta/projects/186742800993/campaigns?alt=json&key=AIzaSyDovLKo3djdRbs963vqKdbj-geRWyzMTrg
Request Method:
POST
Status Code:
200 OK
Remote Address:
172.217.21.10:443
Referrer Policy:
strict-origin-when-cross-origin
```
```
alt=json&key=AIzaSyDovLKo3djdRbs963vqKdbj-geRWyzMTrg
```

```json
{displayName: "ًمرحبا", state: "RUNNING", onStart: {},…}
dataBundle
: 
{}
displayName
: 
"ًمرحبا"
notificationOptions
: 
{messageText: "مرحبا بك في eats!!!", messageTitle: "ًمرحبا", hasSound: false, imageUrl: "",…}
onStart
: 
{}
state
: 
"RUNNING"
targetingConditionJson
: 
"{\"or\":{\"conditions\":[{\"and\":{\"conditions\":[{\"appId\":{\"targetGmpAppId\":\"1:186742800993:ios:32881f94de4b34c77be020\"}}]}},{\"and\":{\"conditions\":[{\"appId\":{\"targetGmpAppId\":\"1:186742800993:ios:add536b3bda638967be020\"}}]}},{\"and\":{\"conditions\":[{\"appId\":{\"targetGmpAppId\":\"1:186742800993:android:a1024998fc035c2e7be020\"}}]}},{\"and\":{\"conditions\":[{\"appId\":{\"targetGmpAppId\":\"1:186742800993:android:6b6450e1dbeb25f47be020\"}}]}},{\"and\":{\"conditions\":[{\"appId\":{\"targetGmpAppId\":\"1:186742800993:android:e8aa879d3ac6f50a7be020\"}}]}},{\"and\":{\"conditions\":[{\"appId\":{\"targetGmpAppId\":\"1:186742800993:ios:e341d0231d8c24747be020\"}}]}}]}}"
```

```json

{"displayName":"ًمرحبا","state":"RUNNING","onStart":{},"notificationOptions":{"messageText":"مرحبا بك في eats!!!","messageTitle":"ًمرحبا","hasSound":false,"imageUrl":"","expiryTime":"2419200s"},"dataBundle":{},"targetingConditionJson":"{\"or\":{\"conditions\":[{\"and\":{\"conditions\":[{\"appId\":{\"targetGmpAppId\":\"1:186742800993:ios:32881f94de4b34c77be020\"}}]}},{\"and\":{\"conditions\":[{\"appId\":{\"targetGmpAppId\":\"1:186742800993:ios:add536b3bda638967be020\"}}]}},{\"and\":{\"conditions\":[{\"appId\":{\"targetGmpAppId\":\"1:186742800993:android:a1024998fc035c2e7be020\"}}]}},{\"and\":{\"conditions\":[{\"appId\":{\"targetGmpAppId\":\"1:186742800993:android:6b6450e1dbeb25f47be020\"}}]}},{\"and\":{\"conditions\":[{\"appId\":{\"targetGmpAppId\":\"1:186742800993:android:e8aa879d3ac6f50a7be020\"}}]}},{\"and\":{\"conditions\":[{\"appId\":{\"targetGmpAppId\":\"1:186742800993:ios:e341d0231d8c24747be020\"}}]}}]}}"}

```



```clur
https://firebasetargeting-pa.clients6.google.com/v1/targeting/estimation?alt=json&key=AIzaSyDovLKo3djdRbs963vqKdbj-geRWyzMTrg
Request Method:
POST
```
```json
alt=json&key=AIzaSyDovLKo3djdRbs963vqKdbj-geRWyzMTrg
```

```
{"condition":[{"or":{"conditions":[{"and":{"conditions":[{"appId":{"targetGmpAppId":"1:186742800993:ios:32881f94de4b34c77be020"}}]}},{"and":{"conditions":[{"appId":{"targetGmpAppId":"1:186742800993:ios:add536b3bda638967be020"}}]}},{"and":{"conditions":[{"appId":{"targetGmpAppId":"1:186742800993:android:a1024998fc035c2e7be020"}}]}},{"and":{"conditions":[{"appId":{"targetGmpAppId":"1:186742800993:android:6b6450e1dbeb25f47be020"}}]}},{"and":{"conditions":[{"appId":{"targetGmpAppId":"1:186742800993:android:e8aa879d3ac6f50a7be020"}}]}},{"and":{"conditions":[{"appId":{"targetGmpAppId":"1:186742800993:ios:e341d0231d8c24747be020"}}]}}]}}],"gmpAppId":["1:186742800993:ios:32881f94de4b34c77be020","1:186742800993:ios:add536b3bda638967be020","1:186742800993:android:a1024998fc035c2e7be020","1:186742800993:android:6b6450e1dbeb25f47be020","1:186742800993:android:e8aa879d3ac6f50a7be020","1:186742800993:ios:e341d0231d8c24747be020"],"requestNodes":{"requestNode":[{"conditionIndex":0,"userData":"frontend-estimation-condition"}]},"service":"NOTIFICATIONS"}

```


```sql

INSERT INTO `item_prices`(`item_id`, `type_service_id`, `price`, `status`) select i.`id`,1,i.`default_price`,'active'   from `items` as i;
INSERT INTO `item_prices`(`item_id`, `type_service_id`, `price`, `status`) select i.`id`,2,i.`default_price`,'active'   from `items` as i;
```


```
SELECT `id`, `code`, `address_id`, `addressInOrder`, CHAR_LENGTH(`addressInOrder`) as addlen
FROM `merchant_orders`
where  CHAR_LENGTH(`addressInOrder`)>2
order by addlen asc
LIMIT 50


update `merchant_orders`
set `addressInOrder`='{}'
where  CHAR_LENGTH(`addressInOrder`)=0


ALTER TABLE `merchant_orders`
CHANGE `addressInOrder` `addressInOrder` json NULL AFTER `address_id`;
```