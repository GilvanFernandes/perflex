```markdown
# Módulo de Validação de E-mail

## Visão Geral

O módulo `email_validator` realiza a verificação diária da validade dos endereços de e-mail cadastrados no banco de dados. Ele efetua uma checagem básica de sintaxe para cada e-mail e envia um alerta para o administrador do sistema caso sejam encontrados e-mails inválidos, listando os nomes completos e os respectivos e-mails dos contatos afetados. Esse módulo contribui para a manutenção de um banco de dados limpo e confiável, promovendo operações mais eficientes.

## Recursos

- **Validação Diária de E-mails**: Executa uma verificação diária em todos os e-mails dos contatos armazenados na tabela de contatos.
- **Validação de Sintaxe**: Utiliza a função `filter_var` do PHP para validar se o e-mail está no formato correto (ex.: `usuario@dominio.com`).
- **Alertas Automáticos**: Envia um e-mail em formato HTML para o administrador contendo uma lista dos contatos com e-mails inválidos.
- **Integração Simples**: Projetado para se integrar facilmente em sistemas que utilizem o framework Codeigniter.

## Requisitos

- **PHP**: Versão 7.4 ou superior.
- **SMTP Configurado**: O sistema precisa ter as configurações SMTP corretamente estabelecidas para o envio de e-mails.
- **Acesso ao Servidor**: Necessário para configurar tarefas cron para a execução automatizada.

## Instalação

Siga os passos abaixo para instalar o módulo `email_validator`:

1. **Upload do Módulo**:
   - Copie a pasta `email_validator` para o diretório de módulos da sua aplicação (exemplo: `/caminho/para/seus/modulos/`).
   - Verifique se a pasta contém os seguintes arquivos essenciais:
     - `init.php`
     - `controllers/EmailValidator.php`

2. **Ativação do Módulo**:
   - Acesse o painel administrativo do seu sistema.
   - Navegue até a área de módulos.
   - Encontre o módulo `Email Validator` na lista e proceda com a ativação.

## Configuração

Para que o módulo funcione corretamente, realize as seguintes configurações:

1. **E-mail do Administrador**:
   - Por padrão, o módulo envia alertas para o e-mail configurado como do administrador.
   - Certifique-se de que o e-mail do administrador esteja correto.
   - Se necessário, edite o arquivo `controllers/EmailValidator.php` para alterar o destinatário dos alertas.

2. **Configurações SMTP**:
   - Verifique se as configurações SMTP do seu sistema (host, porta, usuário e senha) estão corretas.
   - Realize um teste de envio de e-mail para confirmar que a configuração está funcionando.

3. **Configuração da Tarefa Cron**:
   - O módulo utiliza uma tarefa cron para executar a validação diariamente.
   - Configure uma tarefa cron no seu servidor para rodar o script cron do seu sistema pelo menos a cada 5 minutos. Por exemplo:
     ```bash
     */5 * * * * wget -q -O /dev/null http://seudominio.com/cron.php
     ```
   - Substitua `http://seudominio.com/cron.php` pela URL correspondente ao script cron da sua aplicação.
   - Confirme o funcionamento da tarefa verificando os logs do servidor.

## Como Funciona

1. **Execução Diária**:
   - O módulo registra uma tarefa cron denominada `daily_email_validation`, que é executada uma vez por dia, chamando o método `cron_task` presente no controlador `EmailValidator`.

2. **Validação de E-mail**:
   - São recuperados todos os contatos da tabela de contatos, obtendo os campos `firstname`, `lastname` e `email`.
   - Cada e-mail é validado utilizando a função `filter_var($email, FILTER_VALIDATE_EMAIL)` do PHP.
   - Os e-mails que não passarem na validação são coletados, juntamente com os nomes dos respectivos contatos.

3. **Geração do Alerta**:
   - Caso sejam detectados e-mails inválidos, o módulo compila uma lista em formato HTML (por exemplo: "João Silva – email@invalido.com").
   - Um e-mail com o assunto "Relatório Diário de E-mails Inválidos" é enviado ao administrador, contendo a lista dos contatos afetados.

## Limitações

- **Validação Básica**: O módulo realiza apenas uma validação de sintaxe, ou seja, verifica se o e-mail possui um formato válido, sem confirmar se o mesmo é efetivamente entregável ou se o domínio existe.
- **Impacto em Desempenho**: Em bancos de dados com um número elevado de contatos, a execução diária da validação pode afetar a performance do servidor. Para grandes conjuntos de dados, considere otimizar a aplicação, por exemplo, utilizando processamento em lote.
- **Destinatário Único**: Os alertas são enviados apenas para o e-mail do administrador. Se desejar enviar para múltiplos destinatários, será necessário personalizar o código.

## Solução de Problemas

- **Nenhum E-mail Enviado**:
  - Verifique se a tarefa cron está sendo executada corretamente, consultando os logs do servidor.
  - Confirme se as configurações SMTP estão corretas e se o envio de e-mails está funcionando.
  - Certifique-se de que o e-mail do administrador esteja configurado corretamente.

- **Módulo Não Ativado**:
  - Confira se a pasta `email_validator` foi posicionada corretamente no diretório de módulos.
  - Verifique a presença dos arquivos `init.php` e `controllers/EmailValidator.php`.
  - Consulte o painel administrativo para identificar possíveis mensagens de erro durante a ativação.

- **E-mails Inválidos Não Detectados**:
  - Certifique-se de que a tabela de contatos contenha os dados esperados (e-mail, firstname, lastname).
  - Teste a lógica de validação adicionando manualmente um e-mail em formato incorreto (ex.: `teste@`) e confirme se ele é identificado.

## Personalização

- **Validação Avançada**:
  - Integre uma API de verificação de e-mail de terceiros (ex.: NeverBounce, ZeroBounce) para validar a entregabilidade dos e-mails.
  - Modifique o arquivo `EmailValidator.php` para incluir as chamadas à API e aprimorar o processo de validação.

- **Múltiplos Destinatários**:
  - Atualize a lógica de envio de e-mails no arquivo `EmailValidator.php` para incluir destinatários adicionais ou alternativos.

- **Otimização de Performance**:
  - Para bancos de dados grandes, considere implementar o processamento em lote no arquivo `EmailValidator.php` para reduzir a carga do servidor durante as validações.

## Suporte

Se você encontrar problemas ou tiver sugestões de melhoria, entre em contato com o desenvolvedor ou registre uma issue no repositório do projeto. Lembre-se de fornecer detalhes como mensagens de erro, versão do sistema e especificações do servidor para facilitar a análise do problema.

## Licença

Este módulo é fornecido "no estado em que se encontra", sem garantias expressas ou implícitas. Você é livre para usá-lo, modificá-lo e distribuí-lo, respeitando os termos de licença aplicáveis ao seu sistema.
```