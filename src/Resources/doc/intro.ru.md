# Введение

**services.yml**

```yaml
comindware_tracker:
    connections:
        default:
            url: 'http://tracker.example.com'
            token: 'Oosai6aeOoHieth8quooLa3o'
            http:
                client: http_client_service_id
                message_factory: http_message_factory_service_id
                stream_factory: stream_factory_service_id
```
