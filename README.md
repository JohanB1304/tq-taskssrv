# tq-taskssrv
<p align="center">
  <img style="text-align: center;" src="https://www.tiqal.com/wp-content/uploads/2019/09/Offc_TQ_Logo_color-300x148.png">
</p>

[![Demo](https://img.shields.io/badge/demo-online-ed1c46.svg)](https://ngx-scrollbar.netlify.com/)
[![License](https://img.shields.io/npm/l/express.svg?maxAge=2592000)](/LICENSE)

## Table of contents

Components for Tiqal.
1. [Installation](#installation)
1. [Settings](#setting-annotation-component)
1. [Endpoints](#enpoints) 


## <a name="installation"></a> Installation:

**Use the following command to install with composer.**
```bash
composer require tq-taskssrv/tq-taskssrv
```
Register the service provider in your `config/app.php` configuration file:

```php
'providers' => [
    ...
    TqTaskssrv\TqTaskssrv\TasksServiceProvider::class,
],
```

Run the following command to publish the package config file, models files and routes for the the api:
```bash
php artisan vendor:publish --provider="TqTaskssrv\TqTaskssrv\AnnotationsServiceProvider"
```

## <a name="setting-annotation-component"></a>Settings
In the published config file annotation.php you can set the following config values:

```  'base_url'  ``` means the application URL.</br>
```  'ref_entity'  ``` means the reference classes to associate the annotations, two classes that must be separated with '|'.</br>
```  'component_name'  ``` means the component name.</br>
```  'main_table_name'  ``` means the name for the table main table.</br>
```php
return [
    'base_url' => env('AN_BASE_URL','https://signaturesrv.metadockit.com/'),
    'ref_entity' => env('AN_REF_ENTITY','TS_REF_ENTITY','\App\Models\Request::class|\App\Models\Stage::class|'),
    'component_name' => env('AN_COMPONENT_NAME','srv-tasks'),
    'main_table_name' => env('AN_TABLE_NAME','ts_tasks')
];
```

## <a name="endpoints"></a>Endpoints:
The following endpoints are going to be available four its usage:

- Index tasks</br>
  Method: GET</br>
  Path: api/{slug_tenant}/task/{ref_id}</br>
  Parameters:
    - slug_tenant: Slug tenant of the authenticated user.
    - ref_id: Ids of the associated reference entities to the annotation, two uuids that 
    must be separated with '|'.
  </br>
  Responses:
      200:
```json
{
	"data": [
		{
			"id": "999563d5-d1e6-406d-a3f4-424f161c832d",
			"title": "Primera prueba",
			"description": "string",
			"followup_notes": "text",
			"followup_file": "path",
			"verification_notes": "text",
			"verification_file": "path",
			"date_start": "2023-07-06",
			"date_due": "date",
			"responsable": "user-id",
			"tag_id": "tag id",
			"tag_label": "string",
			"type_tag": "string",
			"reopened": false,
			"disabled": false,
			"status": false,
			"verified": false
		}
	],
	"status_code": "200",
	"message": "ok"
}
```
    400:
```json
{
  "status_code": "400",
  "message": "Bad request"
}
```

- Store tasks: </br>
  Method: POST</br>
  Path: api/{slug_tenant}/task/{ref_id}</br>
  Parameters:
    - slug_tenant: Slug tenant of the authenticated user.
    - ref_id: Ids of the associated reference entities to the annotation, two uuids that 
    must be separated with '|'.
  </br>
  Request body:
```json
{
	"title": "Nombre tarea"
}
```
  </br>
    
  responses:
  
    200:
```json
{
	"data": [
		{
			"id": "999563d5-d1e6-406d-a3f4-424f161c832d",
			"title": "Nombre tarea",
			"description": "string",
			"followup_notes": "text",
			"followup_file": "path",
			"verification_notes": "text",
			"verification_file": "path",
			"date_start": "2023-07-06",
			"date_due": "date",
			"responsable": "user id",
			"tag_id": "tag id",
			"tag_label": "string",
			"type_tag": "string",
			"reopened": false,
			"disabled": false,
			"status": false,
			"verified": false
		}
	],
	"status_code": "200",
	"message": "ok"
}
```

- Update tasks:
  Description: The PUT attributes can be null or not be sent.
  Method: PUT</br>
  Path: api/{slug_tenant}/task/{ref_id}/{task_id}</br>
  Parameters:
    - slug_tenant: Slug tenant of the authenticated user.
    - ref_id: Ids of the associated reference entities to the annotation, two uuids that 
    must be separated with '|'.
    - task_id: Task id to update
  </br>
  Request body:
```json
{
    "id": "999563d5-d1e6-406d-a3f4-424f161c832d",
    "title": "Nombre tarea",
    "description": "string",
    "followup_notes": "text",
    "followup_file": "path",
    "verification_notes": "text",
    "verification_file": "path",
    "date_start": "2023-07-06",
    "date_due": "date",
    "responsable": "user id",
    "tag_id": "tag id",
    "tag_label": "string",
    "type_tag": "string",
    "reopened": false,
    "disabled": false,
    "status": false,
    "verified": false
}
```
  Responses:
      200:
```json
{
"data": [
    {
        "id": "999563d5-d1e6-406d-a3f4-424f161c832d",
        "title": "Primera prueba",
        "description": "Prueba",
        "followup_notes": "Prueba seguimiento ejecución",
        "followup_file": null,
        "verification_notes": "Prueba verificación",
        "verification_file": "1688669109_file.gif",
        "date_start": "2023-07-06",
        "date_due": "2023-07-07",
        "responsable": null,
        "tag_id": null,
        "tag_label": null,
        "type_tag": null,
        "reopened": false,
        "disabled": false,
        "status": true,
        "verified": false
    }
],
"status_code": "200",
"message": "ok"
}
```
    400:
```json
{
  "status_code": "400",
  "message": "Bad request"
}
```

- Reorder tasks:
  Description: Reorder tasks manually.
  Method: POST</br>
  Path: api/{slug_tenant}/task/store-order</br>
  Parameters:
    - slug_tenant: Slug tenant of the authenticated user.
  </br>
  Request body:
```json
{
	"tasks":[
		"9993d3a9-bbf5-4825-9b7e-c59bf04e36ed",
		"9993e038-2052-4ead-a6df-c8787b23dd04",
		"9993e0a4-6a03-4744-a37e-81594ddd1b80",
		"9993e0e7-e6af-40c5-8e42-128d4c1ec69f",
		"9993dea5-a456-4270-a52a-cc4d7eaed028",
		"9993deee-23b1-423d-87be-9ffb64d27919",
		"9993df99-99c2-46a3-9a3b-fce89af2a1f9",
		"9993d400-6685-4569-9d83-cdeee06cf32d"
	]
}
```
  Responses:
      200:
```json
{
    "status_code": "200",
    "message": "ok"
}
```
    400:
```json
{
  "status_code": "400",
  "message": "Bad request"
}
```

- Verify specific task:
  Method: POST</br>
  Path: api/{slug_tenant}/task/{ref_id}/{task_id}/verify</br>
  Parameters:
    - slug_tenant: Slug tenant of the authenticated user.
    - ref_id: Ids of the associated reference entities to the annotation, two uuids that 
    must be separated with '|'.
    - task_id: Task id to update
  </br>
  Request body:
```json
{
    "status": true
}
```
  Responses:
      200:
```json
{
"data": {
    "id": "999563d5-d1e6-406d-a3f4-424f161c832d",
    "title": "Primera prueba",
    "description": "Prueba",
    "followup_notes": "Prueba seguimiento ejecución",
    "followup_file": null,
    "verification_notes": "Prueba verificación",
    "verification_file": "1688669109_file.gif",
    "date_start": "2023-07-06",
    "date_due": "2023-07-07",
    "responsable": null,
    "tag_id": null,
    "tag_label": null,
    "type_tag": null,
    "reopened": false,
    "disabled": false,
    "status": true,
    "verified": true
},
"status_code": "200",
"message": "ok"
}
```
    400:
```json
{
  "status_code": "400",
  "message": "Bad request"
}
```

- Disable specific task:
  Method: POST</br>
  Path: api/{slug_tenant}/task/{ref_id}/{task_id}/disable</br>
  Parameters:
    - slug_tenant: Slug tenant of the authenticated user.
    - ref_id: Ids of the associated reference entities to the annotation, two uuids that 
    must be separated with '|'.
    - task_id: Task id to update
  </br>
  Request body:
```json
{
	"justification": "Justificación"
}
```
  Responses:
      200:
```json
{
"data": [
    {
        "id": "999563d5-d1e6-406d-a3f4-424f161c832d",
        "title": "Primera prueba",
        "description": "Prueba",
        "followup_notes": "Prueba seguimiento ejecución",
        "followup_file": null,
        "verification_notes": "Prueba verificación",
        "verification_file": "1688669109_file.gif",
        "date_start": "2023-07-06",
        "date_due": "2023-07-07",
        "responsable": null,
        "tag_id": null,
        "tag_label": null,
        "type_tag": null,
        "reopened": false,
        "disabled": false,
        "status": true,
        "verified": false
    }
],
"status_code": "200",
"message": "ok"
}
```
    400:
```json
{
  "status_code": "400",
  "message": "Bad request"
}
```

- Get total complete tasks</br>
  Method: GET</br>
  Path: api/{slug_tenant}/task/{ref_id}/get_qty_complete_tasks</br>
  Parameters:
    - slug_tenant: Slug tenant of the authenticated user.
    - ref_id: Ids of the associated reference entities to the annotation, two uuids that 
    must be separated with '|'.
  </br>
  Responses:
      200:
```json
{
	"pending_tasks": 7,
	"status_code": "200",
	"message": "ok"
}
```
    400:
```json
{
  "status_code": "400",
  "message": "Bad request"
}
```

- Index tags</br>
  Method: GET</br>
  Path: api/{slug_tenant}/task/{ref_id}/tags/available</br>
  Parameters:
    - slug_tenant: Slug tenant of the authenticated user.
    - ref_id: Ids of the associated reference entities to the annotation, two uuids that 
    must be separated with '|'.
    - search: Query param for searching tags by name.
  </br>
  Responses:
      200:
```json
{
	"data": [
		{
			"id": "999549ac-39d6-4d89-b294-b9132da32bae",
			"label": "Prueba 1",
			"type": null
		}
	],
	"status_code": "200",
	"message": "ok"
}
```
    400:
```json
{
  "status_code": "400",
  "message": "Bad request"
}
```

- List associated tags</br>
  Method: GET</br>
  Path: api/{slug_tenant}/task/{ref_id}/tags</br>
  Parameters:
    - slug_tenant: Slug tenant of the authenticated user.
    - ref_id: Ids of the associated reference entities to the annotation, two uuids that 
    must be separated with '|'.
  </br>
  Responses:
      200:
```json
{
	"data": [
		{
			"id": "999549ac-39d6-4d89-b294-b9132da32bae",
			"label": "Prueba 1",
			"type": null
		}
	],
	"status_code": "200",
	"message": "ok"
}
```
    400:
```json
{
  "status_code": "400",
  "message": "Bad request"
}
```