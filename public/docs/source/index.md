---
title: API Reference

language_tabs:
- bash
- javascript

includes:

search: true

toc_footers:
- <a href='http://github.com/mpociot/documentarian'>Documentation Powered by Documentarian</a>
---
<!-- START_INFO -->
# Info

Welcome to the generated API reference.
[Get Postman Collection](docs/docs/collection.json)

<!-- END_INFO -->

#general
<!-- START_0c068b4037fb2e47e71bd44bd36e3e2a -->
## Authorize a client to access the user&#039;s account.

> Example request:

```bash
curl -X GET -G "/docs/oauth/authorize" 
```
```javascript
const url = new URL("/docs/oauth/authorize");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET oauth/authorize`


<!-- END_0c068b4037fb2e47e71bd44bd36e3e2a -->

<!-- START_e48cc6a0b45dd21b7076ab2c03908687 -->
## Approve the authorization request.

> Example request:

```bash
curl -X POST "/docs/oauth/authorize" 
```
```javascript
const url = new URL("/docs/oauth/authorize");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


### HTTP Request
`POST oauth/authorize`


<!-- END_e48cc6a0b45dd21b7076ab2c03908687 -->

<!-- START_de5d7581ef1275fce2a229b6b6eaad9c -->
## Deny the authorization request.

> Example request:

```bash
curl -X DELETE "/docs/oauth/authorize" 
```
```javascript
const url = new URL("/docs/oauth/authorize");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


### HTTP Request
`DELETE oauth/authorize`


<!-- END_de5d7581ef1275fce2a229b6b6eaad9c -->

<!-- START_a09d20357336aa979ecd8e3972ac9168 -->
## Authorize a client to access the user&#039;s account.

> Example request:

```bash
curl -X POST "/docs/oauth/token" 
```
```javascript
const url = new URL("/docs/oauth/token");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


### HTTP Request
`POST oauth/token`


<!-- END_a09d20357336aa979ecd8e3972ac9168 -->

<!-- START_d6a56149547e03307199e39e03e12d1c -->
## Get all of the authorized tokens for the authenticated user.

> Example request:

```bash
curl -X GET -G "/docs/oauth/tokens" 
```
```javascript
const url = new URL("/docs/oauth/tokens");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET oauth/tokens`


<!-- END_d6a56149547e03307199e39e03e12d1c -->

<!-- START_a9a802c25737cca5324125e5f60b72a5 -->
## Delete the given token.

> Example request:

```bash
curl -X DELETE "/docs/oauth/tokens/1" 
```
```javascript
const url = new URL("/docs/oauth/tokens/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


### HTTP Request
`DELETE oauth/tokens/{token_id}`


<!-- END_a9a802c25737cca5324125e5f60b72a5 -->

<!-- START_abe905e69f5d002aa7d26f433676d623 -->
## Get a fresh transient token cookie for the authenticated user.

> Example request:

```bash
curl -X POST "/docs/oauth/token/refresh" 
```
```javascript
const url = new URL("/docs/oauth/token/refresh");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


### HTTP Request
`POST oauth/token/refresh`


<!-- END_abe905e69f5d002aa7d26f433676d623 -->

<!-- START_babcfe12d87b8708f5985e9d39ba8f2c -->
## Get all of the clients for the authenticated user.

> Example request:

```bash
curl -X GET -G "/docs/oauth/clients" 
```
```javascript
const url = new URL("/docs/oauth/clients");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET oauth/clients`


<!-- END_babcfe12d87b8708f5985e9d39ba8f2c -->

<!-- START_9eabf8d6e4ab449c24c503fcb42fba82 -->
## Store a new client.

> Example request:

```bash
curl -X POST "/docs/oauth/clients" 
```
```javascript
const url = new URL("/docs/oauth/clients");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


### HTTP Request
`POST oauth/clients`


<!-- END_9eabf8d6e4ab449c24c503fcb42fba82 -->

<!-- START_784aec390a455073fc7464335c1defa1 -->
## Update the given client.

> Example request:

```bash
curl -X PUT "/docs/oauth/clients/1" 
```
```javascript
const url = new URL("/docs/oauth/clients/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


### HTTP Request
`PUT oauth/clients/{client_id}`


<!-- END_784aec390a455073fc7464335c1defa1 -->

<!-- START_1f65a511dd86ba0541d7ba13ca57e364 -->
## Delete the given client.

> Example request:

```bash
curl -X DELETE "/docs/oauth/clients/1" 
```
```javascript
const url = new URL("/docs/oauth/clients/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


### HTTP Request
`DELETE oauth/clients/{client_id}`


<!-- END_1f65a511dd86ba0541d7ba13ca57e364 -->

<!-- START_9e281bd3a1eb1d9eb63190c8effb607c -->
## Get all of the available scopes for the application.

> Example request:

```bash
curl -X GET -G "/docs/oauth/scopes" 
```
```javascript
const url = new URL("/docs/oauth/scopes");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET oauth/scopes`


<!-- END_9e281bd3a1eb1d9eb63190c8effb607c -->

<!-- START_9b2a7699ce6214a79e0fd8107f8b1c9e -->
## Get all of the personal access tokens for the authenticated user.

> Example request:

```bash
curl -X GET -G "/docs/oauth/personal-access-tokens" 
```
```javascript
const url = new URL("/docs/oauth/personal-access-tokens");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET oauth/personal-access-tokens`


<!-- END_9b2a7699ce6214a79e0fd8107f8b1c9e -->

<!-- START_a8dd9c0a5583742e671711f9bb3ee406 -->
## Create a new personal access token for the user.

> Example request:

```bash
curl -X POST "/docs/oauth/personal-access-tokens" 
```
```javascript
const url = new URL("/docs/oauth/personal-access-tokens");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


### HTTP Request
`POST oauth/personal-access-tokens`


<!-- END_a8dd9c0a5583742e671711f9bb3ee406 -->

<!-- START_bae65df80fd9d72a01439241a9ea20d0 -->
## Delete the given token.

> Example request:

```bash
curl -X DELETE "/docs/oauth/personal-access-tokens/1" 
```
```javascript
const url = new URL("/docs/oauth/personal-access-tokens/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


### HTTP Request
`DELETE oauth/personal-access-tokens/{token_id}`


<!-- END_bae65df80fd9d72a01439241a9ea20d0 -->

<!-- START_c3fa189a6c95ca36ad6ac4791a873d23 -->
## api/login
> Example request:

```bash
curl -X POST "/docs/api/login" 
```
```javascript
const url = new URL("/docs/api/login");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


### HTTP Request
`POST api/login`


<!-- END_c3fa189a6c95ca36ad6ac4791a873d23 -->

<!-- START_61739f3220a224b34228600649230ad1 -->
## api/logout
> Example request:

```bash
curl -X POST "/docs/api/logout" 
```
```javascript
const url = new URL("/docs/api/logout");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


### HTTP Request
`POST api/logout`


<!-- END_61739f3220a224b34228600649230ad1 -->

<!-- START_3927d9858a98b64816c08b1fb902df4b -->
## api/types
> Example request:

```bash
curl -X GET -G "/docs/api/types" 
```
```javascript
const url = new URL("/docs/api/types");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/types`


<!-- END_3927d9858a98b64816c08b1fb902df4b -->

<!-- START_ee27716666facd0c688d04ba39ec0d48 -->
## api/users/{id}/update_my_user
> Example request:

```bash
curl -X PUT "/docs/api/users/1/update_my_user" 
```
```javascript
const url = new URL("/docs/api/users/1/update_my_user");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


### HTTP Request
`PUT api/users/{id}/update_my_user`


<!-- END_ee27716666facd0c688d04ba39ec0d48 -->

<!-- START_96952d76d1f6c66bfded1a5878b336f0 -->
## api/clientgateways/{id}/test_connection
> Example request:

```bash
curl -X GET -G "/docs/api/clientgateways/1/test_connection" 
```
```javascript
const url = new URL("/docs/api/clientgateways/1/test_connection");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/clientgateways/{id}/test_connection`


<!-- END_96952d76d1f6c66bfded1a5878b336f0 -->

<!-- START_f6323091b158e1df48cc9399ba0e2b22 -->
## api/clientgateways/{id}/execute
> Example request:

```bash
curl -X GET -G "/docs/api/clientgateways/1/execute" 
```
```javascript
const url = new URL("/docs/api/clientgateways/1/execute");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/clientgateways/{id}/execute`


<!-- END_f6323091b158e1df48cc9399ba0e2b22 -->

<!-- START_2e1cf0cd4f38d54b5f160b8e1f8b7985 -->
## api/clientgateways/{id}/execute
> Example request:

```bash
curl -X POST "/docs/api/clientgateways/1/execute" 
```
```javascript
const url = new URL("/docs/api/clientgateways/1/execute");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


### HTTP Request
`POST api/clientgateways/{id}/execute`


<!-- END_2e1cf0cd4f38d54b5f160b8e1f8b7985 -->

<!-- START_956129e38e831ed38015606f5fe59885 -->
## api/clientgateways/{id}/execute_endpoint
> Example request:

```bash
curl -X POST "/docs/api/clientgateways/1/execute_endpoint" 
```
```javascript
const url = new URL("/docs/api/clientgateways/1/execute_endpoint");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


### HTTP Request
`POST api/clientgateways/{id}/execute_endpoint`


<!-- END_956129e38e831ed38015606f5fe59885 -->

<!-- START_1af1a947e16afcb5289fad8940c57ec5 -->
## Display a listing of the resource.

> Example request:

```bash
curl -X GET -G "/docs/api/clients" 
```
```javascript
const url = new URL("/docs/api/clients");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/clients`


<!-- END_1af1a947e16afcb5289fad8940c57ec5 -->

<!-- START_dfd23a7e4e59c7e8fd40b41e652b1be8 -->
## Store a newly created resource in storage.

> Example request:

```bash
curl -X POST "/docs/api/clients" 
```
```javascript
const url = new URL("/docs/api/clients");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


### HTTP Request
`POST api/clients`


<!-- END_dfd23a7e4e59c7e8fd40b41e652b1be8 -->

<!-- START_9176cc1be6ebc014d0f26db8772c607e -->
## Display the specified resource.

> Example request:

```bash
curl -X GET -G "/docs/api/clients/1" 
```
```javascript
const url = new URL("/docs/api/clients/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/clients/{client}`


<!-- END_9176cc1be6ebc014d0f26db8772c607e -->

<!-- START_0a9b19cfbd73bcdb15c3a43a30e80601 -->
## Update the specified resource in storage.

> Example request:

```bash
curl -X PUT "/docs/api/clients/1" 
```
```javascript
const url = new URL("/docs/api/clients/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


### HTTP Request
`PUT api/clients/{client}`

`PATCH api/clients/{client}`


<!-- END_0a9b19cfbd73bcdb15c3a43a30e80601 -->

<!-- START_79aaca474281833ade0b4315f95ce7c8 -->
## Remove the specified resource from storage.

> Example request:

```bash
curl -X DELETE "/docs/api/clients/1" 
```
```javascript
const url = new URL("/docs/api/clients/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


### HTTP Request
`DELETE api/clients/{client}`


<!-- END_79aaca474281833ade0b4315f95ce7c8 -->

<!-- START_fc1e4f6a697e3c48257de845299b71d5 -->
## Display a listing of the resource.

> Example request:

```bash
curl -X GET -G "/docs/api/users" 
```
```javascript
const url = new URL("/docs/api/users");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/users`


<!-- END_fc1e4f6a697e3c48257de845299b71d5 -->

<!-- START_12e37982cc5398c7100e59625ebb5514 -->
## Store a newly created resource in storage.

> Example request:

```bash
curl -X POST "/docs/api/users" 
```
```javascript
const url = new URL("/docs/api/users");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


### HTTP Request
`POST api/users`


<!-- END_12e37982cc5398c7100e59625ebb5514 -->

<!-- START_8653614346cb0e3d444d164579a0a0a2 -->
## Display the specified resource.

> Example request:

```bash
curl -X GET -G "/docs/api/users/1" 
```
```javascript
const url = new URL("/docs/api/users/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/users/{user}`


<!-- END_8653614346cb0e3d444d164579a0a0a2 -->

<!-- START_48a3115be98493a3c866eb0e23347262 -->
## Update the specified resource in storage.

> Example request:

```bash
curl -X PUT "/docs/api/users/1" 
```
```javascript
const url = new URL("/docs/api/users/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


### HTTP Request
`PUT api/users/{user}`

`PATCH api/users/{user}`


<!-- END_48a3115be98493a3c866eb0e23347262 -->

<!-- START_d2db7a9fe3abd141d5adbc367a88e969 -->
## Remove the specified resource from storage.

> Example request:

```bash
curl -X DELETE "/docs/api/users/1" 
```
```javascript
const url = new URL("/docs/api/users/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


### HTTP Request
`DELETE api/users/{user}`


<!-- END_d2db7a9fe3abd141d5adbc367a88e969 -->

<!-- START_9800e65d6ed5a8802de8f431a71a1b29 -->
## Display a listing of the resource.

> Example request:

```bash
curl -X GET -G "/docs/api/clientgateways" 
```
```javascript
const url = new URL("/docs/api/clientgateways");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/clientgateways`


<!-- END_9800e65d6ed5a8802de8f431a71a1b29 -->

<!-- START_4128e6ba45bf4fe2b5794738412d129f -->
## Store a newly created resource in storage.

> Example request:

```bash
curl -X POST "/docs/api/clientgateways" 
```
```javascript
const url = new URL("/docs/api/clientgateways");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


### HTTP Request
`POST api/clientgateways`


<!-- END_4128e6ba45bf4fe2b5794738412d129f -->

<!-- START_15d60e22eb61ed1469650d6622f36a18 -->
## Display the specified resource.

> Example request:

```bash
curl -X GET -G "/docs/api/clientgateways/1" 
```
```javascript
const url = new URL("/docs/api/clientgateways/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/clientgateways/{clientgateway}`


<!-- END_15d60e22eb61ed1469650d6622f36a18 -->

<!-- START_f9ed8d7a23396365677f73ee7a4d01a7 -->
## Update the specified resource in storage.

> Example request:

```bash
curl -X PUT "/docs/api/clientgateways/1" 
```
```javascript
const url = new URL("/docs/api/clientgateways/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


### HTTP Request
`PUT api/clientgateways/{clientgateway}`

`PATCH api/clientgateways/{clientgateway}`


<!-- END_f9ed8d7a23396365677f73ee7a4d01a7 -->

<!-- START_eaec98f8a2f2ff7b05326214a9ecc63c -->
## Remove the specified resource from storage.

> Example request:

```bash
curl -X DELETE "/docs/api/clientgateways/1" 
```
```javascript
const url = new URL("/docs/api/clientgateways/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


### HTTP Request
`DELETE api/clientgateways/{clientgateway}`


<!-- END_eaec98f8a2f2ff7b05326214a9ecc63c -->

<!-- START_d18d782fae1b6dab06f3e3ad296ca00b -->
## Display a listing of the resource.

> Example request:

```bash
curl -X GET -G "/docs/api/clientuserrole" 
```
```javascript
const url = new URL("/docs/api/clientuserrole");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/clientuserrole`


<!-- END_d18d782fae1b6dab06f3e3ad296ca00b -->

<!-- START_957e17e33e27aca66c98afe72afdda58 -->
## Store a newly created resource in storage.

> Example request:

```bash
curl -X POST "/docs/api/clientuserrole" 
```
```javascript
const url = new URL("/docs/api/clientuserrole");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


### HTTP Request
`POST api/clientuserrole`


<!-- END_957e17e33e27aca66c98afe72afdda58 -->

<!-- START_2d7dbf3abd0c1b18885d45dcceab90ed -->
## Display the specified resource.

> Example request:

```bash
curl -X GET -G "/docs/api/clientuserrole/1" 
```
```javascript
const url = new URL("/docs/api/clientuserrole/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/clientuserrole/{clientuserrole}`


<!-- END_2d7dbf3abd0c1b18885d45dcceab90ed -->

<!-- START_4b6391279873d9ff0746e7d13f908b15 -->
## Update the specified resource in storage.

> Example request:

```bash
curl -X PUT "/docs/api/clientuserrole/1" 
```
```javascript
const url = new URL("/docs/api/clientuserrole/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


### HTTP Request
`PUT api/clientuserrole/{clientuserrole}`

`PATCH api/clientuserrole/{clientuserrole}`


<!-- END_4b6391279873d9ff0746e7d13f908b15 -->

<!-- START_b8890507da277d2ab7cb05015af70f76 -->
## Remove the specified resource from storage.

> Example request:

```bash
curl -X DELETE "/docs/api/clientuserrole/1" 
```
```javascript
const url = new URL("/docs/api/clientuserrole/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


### HTTP Request
`DELETE api/clientuserrole/{clientuserrole}`


<!-- END_b8890507da277d2ab7cb05015af70f76 -->

<!-- START_65902301ae6c997afbf1265bb4ddfd5f -->
## Display a listing of the resource.

> Example request:

```bash
curl -X GET -G "/docs/api/requestlogs" 
```
```javascript
const url = new URL("/docs/api/requestlogs");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/requestlogs`


<!-- END_65902301ae6c997afbf1265bb4ddfd5f -->

<!-- START_18dd9a021c86fdfafee5863771db894c -->
## api/requestlogs/all
> Example request:

```bash
curl -X DELETE "/docs/api/requestlogs/all" 
```
```javascript
const url = new URL("/docs/api/requestlogs/all");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


### HTTP Request
`DELETE api/requestlogs/all`


<!-- END_18dd9a021c86fdfafee5863771db894c -->

<!-- START_6c9a1b7d922e0f7d887af2e99cf86361 -->
## Display the specified resource.

> Example request:

```bash
curl -X GET -G "/docs/api/clientuserrole/byuserid/1" 
```
```javascript
const url = new URL("/docs/api/clientuserrole/byuserid/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/clientuserrole/byuserid/{id}`


<!-- END_6c9a1b7d922e0f7d887af2e99cf86361 -->

<!-- START_f78e784cb72641c9f9894e2dd2cef59b -->
## Display the specified resource.

> Example request:

```bash
curl -X GET -G "/docs/api/clientuserrole/byclientanduserid/1/1" 
```
```javascript
const url = new URL("/docs/api/clientuserrole/byclientanduserid/1/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/clientuserrole/byclientanduserid/{client_id}/{user_id}`


<!-- END_f78e784cb72641c9f9894e2dd2cef59b -->

<!-- START_16afe637184aa9af71df6d44d4095b7c -->
## Remove the specified resource from storage.

> Example request:

```bash
curl -X DELETE "/docs/api/clientuserrole/1/1" 
```
```javascript
const url = new URL("/docs/api/clientuserrole/1/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


### HTTP Request
`DELETE api/clientuserrole/{client_id}/{user_id}`


<!-- END_16afe637184aa9af71df6d44d4095b7c -->

<!-- START_1c6358ac3f383ac9a81416415a5f9495 -->
## Update the specified resource in storage.

> Example request:

```bash
curl -X PUT "/docs/api/clientuserrole/1/1" 
```
```javascript
const url = new URL("/docs/api/clientuserrole/1/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


### HTTP Request
`PUT api/clientuserrole/{client_id}/{user_id}`


<!-- END_1c6358ac3f383ac9a81416415a5f9495 -->

<!-- START_ddebad64fab24c650514d236a7680751 -->
## Remove the specified resource from storage.

> Example request:

```bash
curl -X POST "/docs/api/users/changedefault/1" 
```
```javascript
const url = new URL("/docs/api/users/changedefault/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


### HTTP Request
`POST api/users/changedefault/{client_id}`


<!-- END_ddebad64fab24c650514d236a7680751 -->

<!-- START_be14be10a824e0e85c5631f80cf32f58 -->
## api/clientgatewayrelation
> Example request:

```bash
curl -X POST "/docs/api/clientgatewayrelation" 
```
```javascript
const url = new URL("/docs/api/clientgatewayrelation");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


### HTTP Request
`POST api/clientgatewayrelation`


<!-- END_be14be10a824e0e85c5631f80cf32f58 -->

<!-- START_1018b54fbff13c0c2e4d9ce00e141d14 -->
## api/clientgatewayrelation/{clientgatewayrelation}
> Example request:

```bash
curl -X DELETE "/docs/api/clientgatewayrelation/1" 
```
```javascript
const url = new URL("/docs/api/clientgatewayrelation/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


### HTTP Request
`DELETE api/clientgatewayrelation/{clientgatewayrelation}`


<!-- END_1018b54fbff13c0c2e4d9ce00e141d14 -->

<!-- START_5ed24a3de05fdcb502d9775c53b57af1 -->
## api/clientgatewayrelation/getbygatewaytidandcurrentuser/{gatewayId}
> Example request:

```bash
curl -X GET -G "/docs/api/clientgatewayrelation/getbygatewaytidandcurrentuser/1" 
```
```javascript
const url = new URL("/docs/api/clientgatewayrelation/getbygatewaytidandcurrentuser/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```

> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/clientgatewayrelation/getbygatewaytidandcurrentuser/{gatewayId}`


<!-- END_5ed24a3de05fdcb502d9775c53b57af1 -->


