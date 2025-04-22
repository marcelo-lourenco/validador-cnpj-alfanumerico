// Tabela de valores para cálculo do DV (baseado no ASCII)
const valoresCaracteres = {
  '0': 0, '1': 1, '2': 2, '3': 3, '4': 4, '5': 5, '6': 6, '7': 7, '8': 8, '9': 9,
  'A': 17, 'B': 18, 'C': 19, 'D': 20, 'E': 21, 'F': 22, 'G': 23, 'H': 24, 'I': 25,
  'J': 26, 'K': 27, 'L': 28, 'M': 29, 'N': 30, 'O': 31, 'P': 32, 'Q': 33, 'R': 34,
  'S': 35, 'T': 36, 'U': 37, 'V': 38, 'W': 39, 'X': 40, 'Y': 41, 'Z': 42
};

// Pesos para cálculo dos DVs (distribuídos de 2 a 9, da direita para esquerda)
const pesos = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

/**
 * Gera um caractere alfanumérico aleatório (0-9 ou A-Z)
 * @returns {string} Caractere aleatório
 */
function gerarCaractereAleatorio() {
  const caracteres = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
  return caracteres.charAt(Math.floor(Math.random() * caracteres.length));
}

/**
 * Calcula o dígito verificador para um CNPJ alfanumérico
 * @param {string} baseCNPJ - Os 12 caracteres base do CNPJ (sem DV)
 * @returns {string} Os dois dígitos verificadores
 */
function calcularDV(baseCNPJ) {
  const valores = baseCNPJ.split('').map(char => valoresCaracteres[char]);

  // Cálculo do primeiro DV
  let soma1 = 0;
  for (let i = 0; i < 12; i++) {
    soma1 += valores[i] * pesos[i + 1];
  }
  const dv1 = soma1 % 11 < 2 ? 0 : 11 - (soma1 % 11);

  // Cálculo do segundo DV
  let soma2 = 0;
  for (let i = 0; i < 12; i++) {
    soma2 += valores[i] * pesos[i];
  }
  soma2 += dv1 * pesos[12];
  const dv2 = soma2 % 11 < 2 ? 0 : 11 - (soma2 % 11);

  return `${dv1}${dv2}`;
}

/**
 * Formata o CNPJ com máscara (XX.XXX.XXX/XXXX-XX)
 * @param {string} cnpj - CNPJ sem formatação
 * @returns {string} CNPJ formatado
 */
function formatarCNPJ(cnpj) {
  return cnpj.replace(
    /^(\w{2})(\w{3})(\w{3})(\w{4})(\w{2})$/,
    '$1.$2.$3/$4-$5'
  );
}

/**
 * Gera um CNPJ alfanumérico válido
 * @param {boolean} comMascara - Se true, retorna com máscara de formatação
 * @returns {string} CNPJ gerado
 */
function gerarCNPJ(comMascara = false) {
  let base = '';
  for (let i = 0; i < 12; i++) {
    base += gerarCaractereAleatorio();
  }

  const dv = calcularDV(base);
  const cnpjCompleto = base + dv;

  return comMascara ? formatarCNPJ(cnpjCompleto) : cnpjCompleto;
}

/**
 * Atualiza o campo de entrada com o CNPJ gerado
 */
function atualizarCNPJ() {
  const campoCNPJGerado = document.getElementById("cnpjGerado");
  const mensagemGeracao = document.getElementById("mensagemGeracao");
  const alertaGeracao = document.getElementById("alertGeracao");

  try {
    const cnpjGerado = gerarCNPJ(true); // Gera o CNPJ com máscara
    campoCNPJGerado.value = cnpjGerado;

    // Copia o CNPJ gerado para a área de transferência
    navigator.clipboard.writeText(cnpjGerado).then(() => {
      alertaGeracao.className = "alert alert-success mt-3 mb-0";
      mensagemGeracao.innerHTML = `Copiado! ${cnpjGerado}`;
    }).catch(() => {
      alertaGeracao.className = "alert alert-danger mt-3 mb-0";
      mensagemGeracao.innerHTML = "Erro ao copiar o CNPJ para a área de transferência.";
    });
  } catch (erro) {
    alertaGeracao.className = "alert alert-danger mt-3 mb-0";
    mensagemGeracao.innerHTML = "Erro ao gerar CNPJ: " + erro.message;
  }
  resultadoGeracao.style.display = "block";
}

function limpaCNPJ() {
  const campoCNPJGerado = document.getElementById("cnpjGerado");
  const resultadoGeracao = document.getElementById("resultadoGeracao");
  const mensagemGeracao = document.getElementById("mensagemGeracao");
  campoCNPJGerado.value = "";
  resultadoGeracao.style.display = "none";
  mensagemGeracao.innerHTML = "";
}



function calcular() {
  const cnpjSemDV = document.getElementById("cnpjSemDV").value;
  const resultadoCalculoDV = document.getElementById("resultadoCalculoDV");
  const dvCalculado = document.getElementById("dvCalculado");
  const mensagemErroCalculo = document.getElementById("mensagemErroCalculo");
  resultadoCalculoDV.style.display = "none";
  dvCalculado.innerHTML = "";
  mensagemErroCalculo.style.display = "none";
  mensagemErroCalculo.innerHTML = "";
  try {
    const dv = CNPJ.calculaDV(cnpjSemDV);
    dvCalculado.innerHTML = dv;
    resultadoCalculoDV.style.display = "block";
  } catch (erro) {
    mensagemErroCalculo.style.display = "block";
    mensagemErroCalculo.innerHTML = erro.message;
  }
}

function limparCalculo() {
  const cnpjSemDV = document.getElementById("cnpjSemDV");
  const resultadoCalculoDV = document.getElementById("resultadoCalculoDV");
  const dvCalculado = document.getElementById("dvCalculado");
  const mensagemErroCalculo = document.getElementById("mensagemErroCalculo");
  cnpjSemDV.value = "";
  resultadoCalculoDV.style.display = "none";
  dvCalculado.innerHTML = "";
  mensagemErroCalculo.style.display = "none";
  mensagemErroCalculo.innerHTML = "";
}

function validar() {
  const cnpj = document.getElementById("cnpj").value;
  const resultadoValidacao = document.getElementById("resultadoValidacao");
  const mensagemValidacao = document.getElementById("mensagemValidacao");
  const alertaValidacao = document.getElementById("alertValidacao");
  resultadoValidacao.style.display = "none";
  mensagemValidacao.innerHTML = "";
  const valido = CNPJ.isValid(cnpj);
  if (valido) {
    alertaValidacao.className = "alert alert-success mt-3 mb-0";
    mensagemValidacao.innerHTML = "Válido";
  } else {
    alertaValidacao.className = "alert alert-danger mt-3 mb-0";
    mensagemValidacao.innerHTML = "Inválido";
  }
  resultadoValidacao.style.display = "block";
}

function limparValidacao() {
  const cnpj = document.getElementById("cnpj");
  const resultadoValidacao = document.getElementById("resultadoValidacao");
  const mensagemValidacao = document.getElementById("mensagemValidacao");
  cnpj.value = "";
  resultadoValidacao.style.display = "none";
  mensagemValidacao.innerHTML = "";
}

function alternarVisualizacao(idElemento) {
  const elemento = document.getElementById(idElemento);
  if (elemento.style.display === "none") {
    elemento.style.display = "block";
  } else {
    elemento.style.display = "none";
  }
}

function toggleVisualizacao(idElemento) {
  var elemento = document.getElementById(idElemento);
  if (elemento.style.display === "none") {
    elemento.style.display = "block";
  } else {
    elemento.style.display = "none";
  }
}