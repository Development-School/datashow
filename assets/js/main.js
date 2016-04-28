//Formulario
if(jQuery().mask) {
  $(document).ready(function(){
    $('#cpf').mask('000.000.000-00', {reverse: true});
    $('#placa').keyup(function(){
      this.value = this.value.toUpperCase();
    });
    $('#placa').mask('SSS-0000');
    var SPMaskBehavior = function (val) {
      return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
    },
    spOptions = {
      onKeyPress: function(val, e, field, options) {
          field.mask(SPMaskBehavior.apply({}, arguments), options);
        }
    };
    $('#contato1, #contato2').mask(SPMaskBehavior, spOptions);
  });
}