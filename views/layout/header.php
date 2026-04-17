<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal de Adoção</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .navbar-brand { font-weight: 700; color: #e07b39 !important; }
        .card-animal img { width: 100%; height: 200px; object-fit: cover; }
        .badge-cachorro { background: #ffc107; color: #333; }
        .badge-gato     { background: #17a2b8; color: #fff; }
        .badge-papagaio { background: #28a745; color: #fff; }
        .badge-generico { background: #6c757d; color: #fff; }
        .campo-wrapper  { margin-bottom: 1rem; }
        .erro           { color: #dc3545; font-size: .875rem; display: block; }
        .foto-animal    { max-height: 400px; object-fit: cover; width: 100%; border-radius: .5rem; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4">
    <div class="container">
        <a class="navbar-brand" href="index.php">Portal de Adoção</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php?acao=listar">Todos os Animais</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Cadastrar</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="index.php?acao=criar&tipo=cachorro">Cachorro</a></li>
                        <li><a class="dropdown-item" href="index.php?acao=criar&tipo=gato">Gato</a></li>
                        <li><a class="dropdown-item" href="index.php?acao=criar&tipo=papagaio">Papagaio</a></li>
                        <li><a class="dropdown-item" href="index.php?acao=criar&tipo=generico">Outro Animal</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="container">
