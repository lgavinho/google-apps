
## Instalação

Rode **composer install**

## Configuração

Configure as chaves em **app/index.php**

```
gapi.client.setApiKey('APP_KEY'); //set your API KEY

'clientid' : 'ALGO_AQUI.apps.googleusercontent.com', //You need to set client id
```

Salve o arquivo .p12 no servidor. Defina o caminho absoluto no credentials.json (ver abaixo).

Crie o arquivo **credentials.json** na raiz do projeto com a estrutura abaixo:

```
{
  "GoogleServerAPIKey": "",
  "client_id": "",
  "service_account_name": "",
  "key_file_location": "/home/vagrant/Code/XXXX-5da545c52c86.p12",
  "user_to_impersonate": "",
  "domain": "",
  "developerKey" : "",
  "client_oauth_id" : "",
  "client_oauth_secret" : "",
  "key_api_browser" : ""
}
```

