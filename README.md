# Portal de Adoção de Animais — LabOO PHP

Trabalho da disciplina de Laboratório de Orientação a Objetos (LabOO), 10º semestre.
Sistema web para uma ONG fictícia de adoção de animais, desenvolvido em PHP puro sem frameworks, com foco total em OOP.

---

## O que o sistema faz

- Cadastro, edição e exclusão de animais (Cachorro, Gato, Papagaio e outros)
- Upload de foto por animal
- Histórico de saúde de cada animal (vacinas, consultas, cirurgias, etc.)
- Comentários públicos no perfil de cada animal
- Listagem geral com badge colorido por espécie

---

## Banco de dados

Estamos usando **PostgreSQL 17**. O banco se chama `portal_adocao`.

A estrutura de tabelas segue o padrão **Class Table Inheritance** — que espelha diretamente a hierarquia de classes PHP:

- `animais` — tabela base com os campos comuns a todos os animais
- `cachorros`, `gatos`, `papagaios`, `animais_genericos` — tabelas de extensão com campos específicos de cada espécie, ligadas a `animais` por chave estrangeira
- `historico_saude` — registros de saúde vinculados a um animal
- `comentarios` — comentários vinculados a um animal

O schema completo está em `sql/schema.sql`.

---

## Estrutura de pastas

```
portal-adocao/
├── config/          # Conexão com o banco (Database.php — singleton PDO)
├── core/            # Classes base: Controller abstrato e Router
├── models/          # Entidades: Animal (abstrato), Cachorro, Gato, Papagaio, etc.
├── dao/             # Acesso ao banco: interfaces e implementações por espécie
│   └── interfaces/  # IAnimalDAO, IComentarioDAO, IHistoricoDAO
├── forms/           # FormGenerico (abstrato) e todas as subclasses de formulário
│   └── campos/      # Tipos de campo: CampoTexto, CampoSelect, CampoArquivo, etc.
├── controllers/     # AnimalController, ComentarioController, HistoricoController
├── factories/       # FormFactory, AnimalFactory, DAOFactory
├── services/        # UploadService (lida com $_FILES e move_uploaded_file)
├── views/           # Arquivos PHP de apresentação (layout, animal, historico, errors)
├── uploads/animais/ # Fotos enviadas (ignorado pelo git)
└── sql/schema.sql   # Script de criação do banco
```

---

## Como os conceitos de OOP aparecem no código

### Herança em vários níveis

```
Animal (abstrato)
├── Cachorro
├── Gato
├── Papagaio
└── AnimalGenerico

Campo (abstrato)
├── CampoTexto
│   └── CampoEmail        ← herança de dois níveis
├── CampoSelect
├── CampoArquivo
└── ...

FormGenerico (abstrato)
├── FormsCadastroCachorro
├── FormsCadastroGato
└── ...

Controller (abstrato)
├── AnimalController
├── ComentarioController
└── HistoricoController

AnimalDAO
├── CachorroDAO
├── GatoDAO
└── PapagaioDAO
```

### Polimorfismo

O lugar mais claro de polimorfismo é dentro de `FormGenerico::renderizar()` e `FormGenerico::validar()`: o método itera um array de `Campo[]` e chama `$campo->renderizar()` / `$campo->validar()` sem saber se é um `CampoTexto`, `CampoSelect` ou `CampoArquivo`. Cada campo sabe se renderizar e se validar.

O mesmo acontece na listagem de animais: `$animal->renderizarBadge()` é chamado em um array misto de `Cachorro`, `Gato` e `Papagaio`, e cada um retorna seu próprio badge HTML colorido.

### Encapsulamento

Todos os atributos de `Animal` são `private` — nenhum código externo acessa `$animal->nome` diretamente, sempre passa pelo getter/setter. O mesmo vale para `FormGenerico::$campos` (protected) e para o Singleton `Database`, cujo construtor é `private`.

### Abstração

`Animal`, `Campo`, `FormGenerico` e `Controller` são classes abstratas — não dá para instanciar diretamente. Cada uma define o contrato que as subclasses têm que cumprir. As interfaces `IAnimalDAO`, `IComentarioDAO` e `IHistoricoDAO` definem o contrato dos DAOs — o `AnimalController` recebe `IAnimalDAO` no construtor e não sabe qual DAO concreto está usando.

### Template Method (padrão de projeto)

`FormGenerico::__construct()` chama `$this->definirCampos()`, que é abstrato. O pai define o esqueleto: construir → definir campos → pronto para usar. A subclasse só preenche a parte variável (quais campos existem). Isso garante que todo formulário seja sempre inicializado corretamente.

---

## Como rodar

### 1. Pré-requisitos

- PHP 8.x (usamos o built-in server, sem Apache/Nginx)
- PostgreSQL 17
- Extensão `pdo_pgsql` habilitada no `php.ini`

### 2. Banco de dados

Crie o banco e aplique o schema:

```bash
createdb -U postgres portal_adocao
psql -U postgres -d portal_adocao -f sql/schema.sql
```

### 3. Configurar a conexão

Copie o arquivo de exemplo e preencha com suas credenciais:

```bash
cp config/Database.example.php config/Database.php
```

Edite `config/Database.php` e coloque seu usuário e senha do PostgreSQL.

### 4. Subir o servidor

```bash
php -S localhost:8080 -t /caminho/para/portal-adocao
```

Acesse **http://localhost:8080** no navegador.

---

## Observações

- O arquivo `config/Database.php` está no `.gitignore` para não vazar credenciais. Use o `Database.example.php` como base.
- A pasta `uploads/animais/` também está no `.gitignore` — as fotos ficam só na máquina local.
- Não há autenticação no sistema (decisão de escopo do trabalho).
- O front-end usa Bootstrap 5 via CDN, sem nenhum build step.
