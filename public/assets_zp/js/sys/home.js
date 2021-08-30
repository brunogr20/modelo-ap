 am4core.useTheme(am4themes_dataviz);

 let colorTema = '#000',
 colorBar = '#d9d9d9';
 
 if(objPage.isDark){
   colorTema = '#777';
   colorBar = '#2b3e52';
 }
 
am4core.useTheme(am4themes_animated);
var chart = am4core.create("chartAccesses", am4charts.XYChart);
chart.paddingRight = 20;

var cData = [];
var visits = 10;
var previousValue;
for(let day in dataAccesses){
  let d =dataAccesses[day];
  let accesses=d['accesses'];
  cData.push({ date: new Date(d['year'],parseInt(d['month'])-1,d['day']), value: accesses});
  let t=cData.length;
  if(t>1){
   cData[t-2].color = (prevAccesses <= accesses)?'#2af275':'#5e62d2';
  }
  prevAccesses=accesses;
}
chart.data = cData;
var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
dateAxis.renderer.grid.template.location = 0;
dateAxis.renderer.axisFills.template.disabled = true;
dateAxis.renderer.ticks.template.disabled = true;
dateAxis.renderer.labels.template.fill = am4core.color(colorTema);

var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
valueAxis.tooltip.disabled = true;
valueAxis.renderer.minWidth = 35;
valueAxis.renderer.axisFills.template.disabled = true;
valueAxis.renderer.ticks.template.disabled = true;
valueAxis.renderer.labels.template.fill = am4core.color(colorTema);
valueAxis.min = 0;
var series = chart.series.push(new am4charts.LineSeries());
series.dataFields.dateX = "date";
series.dataFields.valueY = "value";
series.strokeWidth = 2;
series.minimum =0;
series.tooltipText = "{value} acesso(s)";
let labelBullet = series.bullets.push(new am4charts.LabelBullet());
labelBullet.label.text = "{value}";
labelBullet.label.horizontalCenter = "left";
labelBullet.label.dx = 14;
labelBullet.label.fill = am4core.color(colorTema);
// set stroke property field
series.propertyFields.stroke = "color";
chart.cursor = new am4charts.XYCursor();
let scrollbarX = new am4core.Scrollbar();
chart.scrollbarX = scrollbarX;
chart.scrollbarX.background.fill = am4core.color(colorBar);
chart.scrollbarX.background.stroke =am4core.color(colorBar);
chart.scrollbarX.thumb.background.fill = am4core.color(colorBar);
chart.scrollbarX.startGrip.background.fill = am4core.color(colorBar);
chart.scrollbarX.endGrip.background.fill = am4core.color(colorBar);
chart.scrollbarX.endGrip.stroke = am4core.color(colorBar);
chart.scrollbarX.stroke = am4core.color(colorBar);
//chart.scrollbarX.thumb.background.states.getKey('hover').properties.fill = am4core.color("#0c141c");
//chart.scrollbarX.thumb.background.states.getKey('down').properties.fill = am4core.color("#0c141c");
chart.events.on("ready", function(ev) {
  let tChart = chart.data.length;
  if(tChart>10){
    dateAxis.zoomToDates(
      chart.data[tChart-10].date,
      chart.data[tChart].date
    );
  }
});
//chart action
var chart2 = am4core.create("charsAction", am4charts.PieChart);

/// minhas atividades
chart2.data = [];
for(let i in dataActions){
  chart2.data.push({
    per:dataActions[i].per,
    title:dataActions[i].title,
  });
}

// Add and configure Series
var pieSeries = chart2.series.push(new am4charts.PieSeries());
pieSeries.dataFields.value = "per";
pieSeries.dataFields.category = "title";
pieSeries.innerRadius = am4core.percent(50);
pieSeries.ticks.template.disabled = true;
pieSeries.labels.template.disabled = true;

chart2.legend = new am4charts.Legend();
chart2.legend.size =10;
chart2.legend.fontSize=22;

chart2.legend.background.fillOpacity = 0.05;
chart2.legend.fixedWidthGrid = true;

chart2.legend.fill  =am4core.color(colorTema);
chart2.legend.position = "right";
chart2.legend.scale = 0.7;
var markerTemplate = chart2.legend.markers.template;
markerTemplate.width = 10;
markerTemplate.height = 10;

function updateColorChartHome(){
  let color  ='#000',colorBar='#d9d9d9';
  if(objPage.isDark){
    color='#777';
    colorBar = '#2b3e52';
  }
  //access
  dateAxis.renderer.labels.template.fill = am4core.color(color);
  valueAxis.renderer.labels.template.fill = am4core.color(color);
  labelBullet.label.fill = am4core.color(color);

  chart.scrollbarX.background.fill = am4core.color(colorBar);
  chart.scrollbarX.background.stroke =am4core.color(colorBar);
  chart.scrollbarX.thumb.background.fill = am4core.color(colorBar);
  chart.scrollbarX.startGrip.background.fill = am4core.color(colorBar);
  chart.scrollbarX.endGrip.background.fill = am4core.color(colorBar);
  chart.scrollbarX.endGrip.stroke = am4core.color(colorBar);
  chart.scrollbarX.stroke = am4core.color(colorBar);
  //// atictivitis

}
