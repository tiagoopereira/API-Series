# API-Series
### Necessário:
    - Docker e docker-compose.
    - Composer.
    - Make.
### Execução:
    - make run
    - porta: 8000

#### Rotas:
  ```/api/auth/login [POST] => Login do usuário.```
  - Payload: <br/>
    ```json
        {
            "email": "string",
            "password": "string"
        }
    ```

  - <b>Auth:</b>
      - <b>Séries:</b> <br/>
          ```/api/series [POST] => Criar uma série.```  <br/>
          - Payload: <br/>
              ```json
                {
                    "name": "string"
                }
              ```
          ```/api/series [GET] => Listar todas as séries.``` <br/>
          ```/api/series/{id} [GET] => Visualizar uma série.``` <br/>
          ```/api/series/{id} [PUT] => Atualizar uma série.``` <br/>
          - Payload:
              ```json 
                {
                    "name": "string"
                }
              ```
          ```/api/series/{id} [DELETE] => Deletar uma série.``` <br/>
          ```/api/series/{id}/episdoes [GET] => Listar os episódios uma série.``` <br/>

      - <b>Episódios:</b> <br/>
          ```/api/episodes [POST] => Criar um episódio.``` <br/>
        - Payload:
            ```json 
                { 
                    "season": "int",
                    "number": "int",
                    "serie_id": "int" 
                }
            ```
        ```/api/episodes [GET] => Listar todos os episódio.``` <br/>
        ```/api/episodes/{id} [GET] => Visualizar um episódio.``` <br/>
        ```/api/episodes/{id} [PUT] => Atualizar um episódio.``` <br/>
         - Payload:
              ```json 
                { 
                    "season": "int",
                    "number": "int",
                    "serie_id": "int" 
                }
              ```
        ```/api/episodes/{id} [DELETE] => Deletar um episódio.```
