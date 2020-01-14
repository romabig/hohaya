var my_skins = ["skin-blue", "skin-black", "skin-red", "skin-yellow", "skin-purple", "skin-green"];
$(function () {
  /* For demo purposes */
  var demo = $("<div />").css({
    position: "fixed",
    top: "90px",
    right: "0",
    background: "#fff",
    "border-radius": "5px 0px 0px 5px",
    padding: "10px 15px",
    "font-size": "16px",
    "z-index": "99999",
    cursor: "pointer",
    color: "#3c8dbc",
    "box-shadow": "0 1px 3px rgba(0,0,0,0.1)"
  }).html("<i class='fa fa-gear'></i>").addClass("no-print");

  var demo_settings = $("<div />").css({
    "padding": "10px",
    position: "fixed",
    top: "90px",
    right: "-250px",
    background: "#fff",
    border: "0px solid #ddd",
    "width": "250px",
    "z-index": "99999",
    "box-shadow": "0 1px 3px rgba(0,0,0,0.1)"
  }).addClass("no-print");
  demo_settings.append(
          "<h4 class='text-light-blue' style='margin: 0 0 5px 0; border-bottom: 1px solid #ddd; padding-bottom: 15px;'>Statistiques</h4>"
          //Fixed layout
          + "<div class='form-group'>"
          + "<div class='checkbox'>"
          + "<label>"
          + "<input type='checkbox' onchange='change_layout(\"fixed\");'/> "
          + "Fixed layout"
          + "</label>"
          + "</div>"
          + "</div>"
          //Boxed layout
          + "<div class='form-group'>"
          + "<div class='checkbox'>"
          + "<label>"
          + "<input type='checkbox' onchange='change_layout(\"layout-boxed\");'/> "
          + "Boxed Layout"
          + "</label>"
          + "</div>"
          + "</div>"
          //Sidebar Collapse
          + "<div class='form-group'>"
          + "<div class='checkbox'>"
          + "<label>"
          + "<input type='checkbox' onchange='change_layout(\"sidebar-collapse\");'/> "
          + "Collapsed Sidebar"
          + "</label>"
          + "</div>"
          + "</div>"
          );


  
  

  

  demo.click(function () {
    if (!$(this).hasClass("open")) {
      $(this).animate({"right": "250px"});
      demo_settings.animate({"right": "0"});
      $(this).addClass("open");
    } else {
      $(this).animate({"right": "0"});
      demo_settings.animate({"right": "-250px"});
      $(this).removeClass("open");
    }
  });

  $("body").append(demo);
  $("body").append(demo_settings);

  setup();
});

function change_layout(cls) {
  $("body").toggleClass(cls);
  $.AdminLTE.layout.fixSidebar();  
}
function change_skin(cls) {  
  $.each(my_skins, function (i) {
    $("body").removeClass(my_skins[i]);
  });

  $("body").addClass(cls);
  store('skin', cls);
  return false;
}



function setup() {
  var tmp = get('skin');
  if (tmp && $.inArray(tmp, my_skins))
    change_skin(tmp);
}