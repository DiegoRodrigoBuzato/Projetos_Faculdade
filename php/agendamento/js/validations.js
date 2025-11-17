// js/validations.js

// Validação de CPF
function validarCPF(cpf) {
    cpf = cpf.replace(/[^\d]/g, '');
    
    if (cpf.length !== 11 || /^(\d)\1{10}$/.test(cpf)) {
        return false;
    }
    
    let soma = 0;
    let resto;
    
    for (let i = 1; i <= 9; i++) {
        soma += parseInt(cpf.substring(i - 1, i)) * (11 - i);
    }
    
    resto = (soma * 10) % 11;
    if (resto === 10 || resto === 11) resto = 0;
    if (resto !== parseInt(cpf.substring(9, 10))) return false;
    
    soma = 0;
    for (let i = 1; i <= 10; i++) {
        soma += parseInt(cpf.substring(i - 1, i)) * (12 - i);
    }
    
    resto = (soma * 10) % 11;
    if (resto === 10 || resto === 11) resto = 0;
    if (resto !== parseInt(cpf.substring(10, 11))) return false;
    
    return true;
}

// Formatação de CPF
function formatarCPF(input) {
    let cpf = input.value.replace(/\D/g, '');
    
    if (cpf.length <= 11) {
        cpf = cpf.replace(/(\d{3})(\d)/, '$1.$2');
        cpf = cpf.replace(/(\d{3})(\d)/, '$1.$2');
        cpf = cpf.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
    }
    
    input.value = cpf;
}

// Formatação de telefone
function formatarTelefone(input) {
    let telefone = input.value.replace(/\D/g, '');
    
    if (telefone.length <= 11) {
        if (telefone.length <= 10) {
            telefone = telefone.replace(/(\d{2})(\d)/, '($1) $2');
            telefone = telefone.replace(/(\d{4})(\d)/, '$1-$2');
        } else {
            telefone = telefone.replace(/(\d{2})(\d)/, '($1) $2');
            telefone = telefone.replace(/(\d{5})(\d)/, '$1-$2');
        }
    }
    
    input.value = telefone;
}

// Validação de email
function validarEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

// Validação de data
function validarDataFutura(data) {
    const dataInput = new Date(data);
    const hoje = new Date();
    hoje.setHours(0, 0, 0, 0);
    
    return dataInput >= hoje;
}

// Validação de horário comercial
function validarHorarioComercial(hora) {
    const [h, m] = hora.split(':');
    const horario = parseInt(h);
    
    return horario >= 8 && horario < 18;
}

// Confirmação de exclusão
function confirmarExclusao(mensagem) {
    return confirm(mensagem || 'Tem certeza que deseja excluir?');
}

// Máscara apenas números
function apenasNumeros(input) {
    input.value = input.value.replace(/\D/g, '');
}

// Validação de senha forte
function validarSenhaForte(senha) {
    const minLength = 6;
    const hasUpperCase = /[A-Z]/.test(senha);
    const hasLowerCase = /[a-z]/.test(senha);
    const hasNumbers = /\d/.test(senha);
    
    return senha.length >= minLength && hasUpperCase && hasLowerCase && hasNumbers;
}

// Comparar senhas
function compararSenhas(senha1, senha2) {
    return senha1 === senha2;
}

// Prevenir envio duplo de formulário
function prevenirEnvioDuplo(form) {
    const submitBtn = form.querySelector('button[type="submit"]');
    
    form.addEventListener('submit', function() {
        submitBtn.disabled = true;
        submitBtn.textContent = 'Processando...';
        
        setTimeout(function() {
            submitBtn.disabled = false;
            submitBtn.textContent = submitBtn.getAttribute('data-original-text') || 'Enviar';
        }, 3000);
    });
}

// Inicialização automática
document.addEventListener('DOMContentLoaded', function() {
    // CPF inputs
    const cpfInputs = document.querySelectorAll('input[name="cpf"]');
    cpfInputs.forEach(input => {
        input.addEventListener('blur', function() {
            const cpf = this.value.replace(/\D/g, '');
            if (cpf.length === 11 && !validarCPF(cpf)) {
                alert('CPF inválido!');
                this.value = '';
                this.focus();
            }
        });
    });
    
    // Telefone inputs
    const telefoneInputs = document.querySelectorAll('input[name="telefone"]');
    telefoneInputs.forEach(input => {
        input.addEventListener('input', function() {
            formatarTelefone(this);
        });
    });
    
    // Email inputs
    const emailInputs = document.querySelectorAll('input[type="email"]');
    emailInputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value && !validarEmail(this.value)) {
                alert('Email inválido!');
                this.focus();
            }
        });
    });
    
    // Data inputs
    const dataInputs = document.querySelectorAll('input[type="date"]');
    dataInputs.forEach(input => {
        if (input.hasAttribute('min') && input.getAttribute('min') === new Date().toISOString().split('T')[0]) {
            input.addEventListener('change', function() {
                if (!validarDataFutura(this.value)) {
                    alert('Não é possível selecionar data passada!');
                    this.value = '';
                }
            });
        }
    });
    
    // Hora inputs
    const horaInputs = document.querySelectorAll('input[type="time"]');
    horaInputs.forEach(input => {
        input.addEventListener('change', function() {
            if (!validarHorarioComercial(this.value)) {
                alert('Horário de atendimento: 08:00 às 18:00');
                this.value = '';
            }
        });
    });
    
    // Prevenir envio duplo em todos os formulários
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        prevenirEnvioDuplo(form);
    });
});