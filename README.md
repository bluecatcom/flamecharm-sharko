# flamecharm-sharko

GERENCIADOR DE TAREFAS - PHP E MARIADB

REQUISITOS:
- PHP 8.1+ com extensões PDO MySQL e mbstring
- MariaDB, Apache e phpMyAdmin (ex.: XAMPP)

INSTALAÇÃO:
1. Extraia a pasta gerenciador-tarefas para htdocs (XAMPP).
2. Inicie Apache e MariaDB no XAMPP.
3. Abra http://localhost/phpmyadmin e importe banco.sql na aba SQL/Importar.
4. Ajuste host, banco, usuario e senha em config/conexao.php, se necessário.
5. Abra http://localhost/gerenciador-tarefas/.
6. Clique em Criar conta, cadastre-se e faça login.
7. Abra Gerenciar tarefas para cadastrar, listar, editar e excluir tarefas.

SEGURANÇA:
- PDO com exceções, consultas preparadas e bindValue.
- Senhas com password_hash/password_verify.
- Sessão regenerada após login; logout via POST.
- Token CSRF nos formulários POST e escaping HTML na saída.
- Operações de tarefas limitadas ao usuário autenticado.

ENTREGA:
- Este pacote contém os arquivos-fonte e banco.sql para importar.
- Se o professor exigir uma exportação gerada pelo phpMyAdmin, após criar o banco
  use Exportar > Rápido > SQL e substitua banco.sql pelo arquivo exportado.
