$(document).ready(function(){
  "use strict";

  /**
   * Easy selector helper function
   */
  const select = (el, all = false) => {
    el = el.trim()
    if (all) {
      return [...document.querySelectorAll(el)]
    } else {
      return document.querySelector(el)
    }
  }

  /**
   * Easy event listener function
   */
  const on = (type, el, listener, all = false) => {
    if (all) {
      select(el, all).forEach(e => e.addEventListener(type, listener))
    } else {
      select(el, all).addEventListener(type, listener)
    }
  }

  /**
   * Easy on scroll event listener 
   */
  const onscroll = (el, listener) => {
    el.addEventListener('scroll', listener)
  }

  /**
   * Sidebar toggle
   */
  if (select('.toggle-sidebar-btn')) {
    on('click', '.toggle-sidebar-btn', function(e) {
      select('body').classList.toggle('toggle-sidebar')
    })
  }

  /**
   * Search bar toggle
   */
  if (select('.search-bar-toggle')) {
    on('click', '.search-bar-toggle', function(e) {
      select('.search-bar').classList.toggle('search-bar-show')
    })
  }

  /**
   * Navbar links active state on scroll
   */
  let navbarlinks = select('#navbar .scrollto', true)
  const navbarlinksActive = () => {
    let position = window.scrollY + 200
    navbarlinks.forEach(navbarlink => {
      if (!navbarlink.hash) return
      let section = select(navbarlink.hash)
      if (!section) return
      if (position >= section.offsetTop && position <= (section.offsetTop + section.offsetHeight)) {
        navbarlink.classList.add('active')
      } else {
        navbarlink.classList.remove('active')
      }
    })
  }
  window.addEventListener('load', navbarlinksActive)
  onscroll(document, navbarlinksActive)

  /**
   * Toggle .header-scrolled class to #header when page is scrolled
   */
  let selectHeader = select('#header')
  if (selectHeader) {
    const headerScrolled = () => {
      if (window.scrollY > 100) {
        selectHeader.classList.add('header-scrolled')
      } else {
        selectHeader.classList.remove('header-scrolled')
      }
    }
    window.addEventListener('load', headerScrolled)
    onscroll(document, headerScrolled)
  }

  /**
   * Back to top button
   */
  let backtotop = select('.back-to-top')
  if (backtotop) {
    const toggleBacktotop = () => {
      if (window.scrollY > 100) {
        backtotop.classList.add('active')
      } else {
        backtotop.classList.remove('active')
      }
    }
    window.addEventListener('load', toggleBacktotop)
    onscroll(document, toggleBacktotop)
  }

  /**
   * Initiate tooltips
   */
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
  var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
  })

  /**
   * Initiate Bootstrap validation check
   */
  var needsValidation = document.querySelectorAll('.needs-validation')

  Array.prototype.slice.call(needsValidation)
    .forEach(function(form) {
      form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }

        form.classList.add('was-validated')
      }, false)
    })

  /**
   * Initiate Datatables
   */
  // const datatables = select('.datatable', true)
  // datatables.forEach(datatable => {
  //   new simpleDatatables.DataTable(datatable);
 // })
   /**
   * Autoresize echart charts
   */
  const mainContainer = select('#main');
  if (mainContainer) {
    setTimeout(() => {
      new ResizeObserver(function() {
        select('.echart', true).forEach(getEchart => {
          echarts.getInstanceByDom(getEchart).resize();
        })
      }).observe(mainContainer);
    }, 200);
  }

  carregaHeader();

});

function carregaHeader(){
	var url = $(location).attr('pathname');
  var result = url.split('/');
  
  $.ajax({
    url: "/public/componentes/area-restrita/model/carregaHeader.php",
    dataType: 'JSON',
    type: "POST",
    success: function(data){

      if((data==0 && result[2]!="login") || result[2]==""){
        location.href='https://portal.seds.sp.gov.br/coed/login';
      }
      else{
        
        $(".vwNome").html(data.usuario_nome);
        $(".vwPerfil").html(data.perfil_descricao);
        if(data.perfil==1){
          $("#boxVinculoAtivo").html(data.perfil_descricao);
        }
        else if(data.perfil==8){
          $("#boxVinculoAtivo").html(data.perfil_descricao);
        }
        else if(data.perfil==2){
          $(".vwLocal").html(data.celebrante_nome_fantasia);
          $("#boxVinculoAtivo").html(data.celebrante_nome_fantasia);
        }
        else if(data.perfil==3){
          $(".vwLocal").html(data.municipio_orgao_publico);
          $("#boxVinculoAtivo").html(data.municipio_orgao_publico);
        }
        else if(data.perfil==4){
          $(".vwLocal").html(data.executora_nome_fantasia);
          $("#boxVinculoAtivo").html(data.executora_nome_fantasia);
        }
        else if(data.perfil==7){
          $(".vwLocal").html(data.perfil_descricao);
          $("#boxVinculoAtivo").html(data.perfil_descricao);
        }
        else{}

        carregaMenu(data.perfil);

      }

    }

  });

}

function carregaMenu(perfil){

  switch(String(perfil)){
    case '1':
      $("#titDashboard , #titCadastros , #titPrestacao , #titEncaminhamentosOscs").removeClass('d-none');
      $("#mnuCadAcolhidos , #mnuCadCelebrante , #mnuCadMunicipio , #mnuCadDesligamentos , #mnuCadExecutora , #mnuCadUsuario , #mnuPrestPrestacoes , #mnuPrestCabecalhos").removeClass('d-none');
    break;
    case '2':
      $("#titDashboard , #titCadastros , #titPrestacao").removeClass('d-none');
      $("#mnuCadExecutora , #mnuPrestPrestacoes , #mnuPrestCabecalhos").removeClass('d-none');
    break;
    case '3':
      $("#titDashboard , #titCadastros").removeClass('d-none');
      $("#mnuCadAcolhidos").removeClass('d-none');
    break;
    case '4':
      $("#titDashboard , #titCadastros , #titPrestacao").removeClass('d-none');
      $("#mnuCadAcolhidos , #mnuCadDesligamentos , #mnuPrestPrestacoes").removeClass('d-none');
    break;
    case '6':
      $("#titDashboard , #titPrestacao").removeClass('d-none');
      $("#mnuPrestPrestacoes").removeClass('d-none');
    break;
    case '7':
      $("#titDashboard , #titEncaminhamentosOscs").removeClass('d-none');
    break;
    case '8':
      $("#titDashboard , #titPrestacao").removeClass('d-none');
      $("#mnuPrestPrestacoes , #mnuPrestCabecalhos").removeClass('d-none');
    break;
    default:
      $("#titDashboard , #titPrestacao").removeClass('d-none');
      $("#mnuPrestPrestacoes").removeClass('d-none');  
      //$("#titDashboard , #titCadastros , #titPrestacao").removeClass('d-none');
      //$("#mnuCadAcolhidos , #mnuCadExecutora , #mnuPrestPrestacoes").removeClass('d-none');
    break;
  }

}
