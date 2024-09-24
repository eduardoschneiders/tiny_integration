# tiny_integration

### Run locally command:
`TOKEN=some-random-key EXPIRE_CACHE=10 php -S localhost:8000`

### API docs
https://tiny.com.br/api-docs/
https://tiny.com.br/api-docs/api2-info


Order example: http://localhost:8000/order.php?id=735217815


### Gcloud deploy
- rename `env_variables.example.yml` to `env_variables.yml`.
- replace the TOKEN

- `gcloud app deploy`

- `gcloud app browser`
