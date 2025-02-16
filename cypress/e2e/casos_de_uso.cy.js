Cypress.Commands.add('preserveCookies', () => {
  cy.getCookies().then((cookies) => {
    cookies.forEach((cookie) => {
      Cypress.Cookies.preserveOnce(cookie.name);
    });
  });
});

describe('Teste de Registro', () => {
  before(function() {
    // Comando MySQL executado diretamente via cy.exec() para sempre comecar com usuario nao criado
    cy.wait(1000)
    cy.exec('mysql -h 127.0.0.1 -u root -p"root" -e "DELETE FROM usuarios WHERE email = \'teste@exemplo.com\';" twitter_clone')
  });
    it('Deve registrar um novo usuário com sucesso', () => {
      cy.visit('http://localhost:8080/registrar'); // Página de registro
      cy.wait(1000)
      cy.get('input[name="nome"]').type('Usuário Teste'); // Preenche o nome
      cy.get('input[name="email"]').type('teste@exemplo.com'); // Preenche o e-mail
      cy.get('input[name="senha"]').type('senha123'); // Preenche a senha
      cy.get('button[type="submit"]').click(); // Envia o formulário
  
      // Verifica se o registro foi concluído
      cy.url().should('include', '/registrar'); // Permanece na mesma URL após registro
      cy.contains('Clique aqui para fazer login!').should('be.visible'); // Verifica o texto pós-registro
    });
  
    it('Deve exibir erro ao tentar registrar com campos vazios', () => {
      cy.visit('http://localhost:8080/inscreverse'); // Página de registro
  
      // Testa sem preencher o formulário
      cy.get('button[type="submit"]').click();
  
      // Verifica se as mensagens de erro do navegador aparecem
      //cy.get('input[name="nome"]:invalid').should('exist'); // Campo obrigatório
      //cy.get('input[name="email"]:invalid').should('exist'); // Campo obrigatório
      //cy.get('input[name="senha"]:invalid').should('exist'); // Campo obrigatório
      cy.contains('Clique aqui para fazer login!').should('not.exist'); // Verifica o texto pós-registro
    });
  
    it('Deve exibir erro ao tentar registrar com email inválido', () => {
      cy.visit('http://localhost:8080/registrar'); // Página de registro
      cy.get('input[name="nome"]').type('Usuário Teste'); // Preenche o nome
      cy.get('input[name="email"]').type('em'); // E-mail inválido
      cy.get('input[name="senha"]').type('senha123'); // Preenche a senha
      cy.get('button[type="submit"]').click();
  
      // Testa se o navegador detecta o e-mail inválido
      //cy.get('input[name="email"]:invalid').should('exist'); // Campo inválido
      cy.contains('Clique aqui para fazer login!').should('not.exist'); // Verifica o texto pós-registro
    });
  });  

  describe('Testes de Login', { testIsolation: false }, () => {
    beforeEach(() => {
      // Visita a pagina do servidor onde o HTML esta rodando
      cy.visit('http://localhost:8080');
    });
  
    it('Deve exibir erro com credenciais incorretas', () => {
      // Insere o e-mail e a senha incorretos
      cy.get('input[name="email"]').type('teste@exemplo.com');
      cy.get('input[name="senha"]').type('senhaerrada');
  
      // Clica no botao de login
      cy.get('button[type="submit"]').click();
  
      // Verifica a mensagem de erro
      cy.contains('*Email e ou senha inválido(s').should('exist');
    });      

    it('Deve permitir login com credenciais corretas', () => {
        cy.visit('http://localhost:8080');
        // Insere o e-mail e a senha corretos
        cy.get('input[name="email"]').type('teste@exemplo.com');
        cy.get('input[name="senha"]').type('senha123');
    
        // Clica no botao de login
        cy.get('button[type="submit"]').click();
  
        // Adicionar codigo para validar redirecionamento para /timeline    
        cy.url().should('include','/timeline');        
      });
  });

// logar? ou usar cookie de sessao previa
// fazer um post > ler o dito post > deletar o mesmo?

describe('Teste de Posts', { testIsolation: false }, () => {
  it('Deve criar um novo tweet', () => {
    // Preenche o campo do tweet
    cy.get('#exampleFormControlTextarea1').type('Meu primeiro tweet automatizado com Cypress!');

    // Clica no botao de envio
    cy.get('.btn-primary').contains('Piar').click();  
  });

  it('Deve encontrar um tweet especifico', () =>
  {
      cy.get('.tweet').contains('Meu primeiro tweet automatizado com Cypress!').should('exist');
  })

  it('Deve remover um tweet', () => {
      // Clica no botão de remover do tweet correspondente
      cy.get('.tweet').contains('Meu primeiro tweet automatizado com Cypress!').parent().find('button').click();
  
      // Verifica se o tweet foi removido
      cy.get('.tweet').contains('Meu primeiro tweet automatizado com Cypress!').should('not.exist');
    });
});

describe('template spec', () => {
  it('passes', () => {
    cy.visit('localhost:8080')
  })
})

describe('Testar seguir e deixar de seguir', () => {
  beforeEach(() => {
      // Acessar a página inicial
      cy.visit('http://localhost:8080');
  });

  it('Deve pesquisar um usuário e segui-lo', () => {
      // Pesquisa um usuário
      cy.get('input[pesquisarPor="Quem você está procurando?"]').type('paunoseucu'); //MUDA O BAGUI PRA UM USUARIO Q JA EXISTA SEM SER VC
      cy.get('button').contains('Procurar').click();

      // Verifica se o usuário apareceu e clica em "Seguir"
      cy.contains('paunoseucu').parent().within(() => {
          cy.contains('Seguir').click();
      });
	  // aqui muda pois ele da refresh na página
	  cy.get('input[pesquisarPor="Quem você está procurando?"]').type('paunoseucu'); //MUDA O BAGUI PRA UM USUARIO Q JA EXISTA SEM SER VC
      cy.get('button').contains('Procurar').click();
	  // passo repetido ^

      // Valida que o botão mudou para "Deixar de seguir"
      cy.contains('paunoseucu').parent().within(() => {
          cy.contains('Deixar de seguir').should('exist');
      });
  });

  it('Deve pesquisar um usuário e deixar de segui-lo', () => {
      // Pesquisa um usuário
      cy.get('input[pesquisarPor="Quem você está procurando?"]').type('paunoseucu'); //MUDA O BAGUI PRA UM USUARIO Q JA EXISTA SEM SER VC
      cy.get('button').contains('Procurar').click();

      // Verifica se o usuário apareceu e clica em "Deixar de seguir"
      cy.contains('paunoseucu').parent().within(() => {
          cy.contains('Deixar de seguir').click();
      });
	  
	  // aqui muda pois ele da refresh na página
	  cy.get('input[pesquisarPor="Quem você está procurando?"]').type('paunoseucu'); //MUDA O BAGUI PRA UM USUARIO Q JA EXISTA SEM SER VC
      cy.get('button').contains('Procurar').click();
	  // passo repetido ^

      // Valida que o botão mudou para "Seguir"
      cy.contains('Usuário').parent().within(() => {
          cy.contains('Seguir').should('exist');
      });
  });
});