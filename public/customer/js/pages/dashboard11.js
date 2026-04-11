//[Dashboard Javascript]

//Project:	Superieur Admin - Responsive Admin Template
//Primary use:   Used only for the main dashboard (index.html)



$(function () {

  'use strict';
	
	
	var options = {
          series: [{
            name: "Revenue",
            data: [48, 65, 74, 100, 65, 45]
        }],
          chart: {
			  foreColor:"#5a789b",
          height: 150,
          type: 'area',
          zoom: {
            enabled: false
          },			  
		  toolbar: {
			show: false,
		  }
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          curve: 'smooth'
        },
		colors: ['#7a15f7'],
        grid: {			
			show: false,
			padding: {
			  top: -50,
			  bottom: -20,
			  right: 0,
			  left: -10
			},
        },
		
		 legend: {
      		show: false,
		 },
        xaxis: {
          categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
			labels: {
          		show: false,
			},
			axisBorder: {
          		show: false,
			},
			axisTicks: {
          		show: false,
			},
        	},
		
        yaxis: {
          labels: {
          		show: false,
			}
        },
        };

        var chart = new ApexCharts(document.querySelector("#revenue1"), options);
        chart.render();
	
	
	
	
	
	var options = {
          series: [{
            name: "Revenue",
            data: [91, 80, 70, 60, 71, 75]
        }],
          chart: {
			  foreColor:"#5a789b",
          height: 150,
          type: 'area',
          zoom: {
            enabled: false
          },			  
		  toolbar: {
			show: false,
		  }
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          curve: 'smooth'
        },
		colors: ['#FFB800'],
        grid: {			
			show: false,
			padding: {
			  top: -50,
			  bottom: -20,
			  right: 0,
			  left: -10
			},
        },
		
		 legend: {
      		show: false,
		 },
        xaxis: {
          categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
			labels: {
          		show: false,
			},
			axisBorder: {
          		show: false,
			},
			axisTicks: {
          		show: false,
			},
        	},
		
        yaxis: {
          labels: {
          		show: false,
			}
        },
        };

        var chart = new ApexCharts(document.querySelector("#revenue2"), options);
        chart.render();
	
	
	
	var options = {
          series: [{
          name: 'Net Profit',
          data: [44, 55, 57, 56, 61, 58, 63, 55, 60, 25, 85]
        }, {
          name: 'Revenue',
          data: [76, 85, 101, 98, 87, 105, 91, 65, 110, 50, 100]
        }],
          chart: {
          type: 'bar',
			  foreColor:"#5a789b",
          height: 220,
			  toolbar: {
        		show: false,
			  }
        },
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '25%',
            endingShape: 'rounded'
          },
        },
        dataLabels: {
          enabled: false,
        },
		grid: {
			show: true,			
		},
        stroke: {
          show: true,
          width: 2,
          colors: ['transparent']
        },
		colors: ['#EF3737', '#0F5EF7'],
        xaxis: {
          categories: ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
			
        },
        yaxis: {
          
        },
		 legend: {
      		show: false,
		 },
        fill: {
          opacity: 1
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return "$ " + val + " thousands"
            }
          },
			marker: {
			  show: false,
		  },
        }
        };

        var chart = new ApexCharts(document.querySelector("#recent_trend"), options);
        chart.render();
	
	
	var options = {
      chart: {
        height: 290,
        type: 'area',
        zoom: {
          enabled: false
        },
      },
      dataLabels: {
        enabled: false
      },
	  colors: ["#5949d6", '#007eff', '#2a8853'],
      stroke: {
        width: [3, 3, 3],
        curve: 'smooth',
        dashArray: [0, 0, 0]
      },
      series: [{
          name: "In Store",
          data: [45, 52, 38, 24, 33, 26, 21, 20, 6, 8, 15, 10]
        },
        {
          name: "Online",
          data: [35, 41, 62, 42, 13, 18, 29, 37, 36, 51, 32, 35]
        },
        {
          name: 'Total Visits',
          data: [87, 57, 74, 99, 75, 38, 62, 47, 82, 56, 45, 47]
        }
      ],
      markers: {
        size: 0,

        hover: {
          sizeOffset: 6
        }
      },
      xaxis: {
        categories: ['01 Jan', '02 Jan', '03 Jan', '04 Jan', '05 Jan', '06 Jan', '07 Jan', '08 Jan', '09 Jan',
          '10 Jan', '11 Jan', '12 Jan'
        ],
      },
		
		 legend: {
      		show: false,
		 },
      tooltip: {
        y: [{
          title: {
            formatter: function (val) {
              return val + " (Avg)"
            }
          }
        }, {
          title: {
            formatter: function (val) {
              return val + " Avg"
            }
          }
        }, {
          title: {
            formatter: function (val) {
              return val;
            }
          }
        }]
      },
      grid: {
        borderColor: '#f1f1f1',
      }
    }
    
    var chart = new ApexCharts(
      document.querySelector("#sales-overview"),
      options
    );

    chart.render();
	
	
	
	
	
	var options = {
          series: [44, 55],
          chart: {
			  foreColor:"#5a789b",
          type: 'donut',
			 height: 150,
			  width: 150,
        },
		legend: {
      		show: false,
		},
		dataLabels: {
			enabled: false,
		  },
		plotOptions: {
			pie: {
			  customScale: 0.8,
			  donut: {
				size: '80%',
			  },
			  offsetY: 0,
			},
			stroke: {
			  colors: undefined
			}
		  },
		colors:['#00BC8B', '#0F5EF7'],
        };

        var chart = new ApexCharts(document.querySelector("#chart41"), options);
        chart.render();
	
	
		
	
	
	var options = {
          series: [44, 55],
          chart: {
			  foreColor:"#5a789b",
          type: 'donut',
			 height: 150,
			  width: 150,
        },
		legend: {
      		show: false,
		},
		dataLabels: {
			enabled: false,
		  },
		plotOptions: {
			pie: {
			  customScale: 0.8,
			  donut: {
				size: '80%',
			  },
			  offsetY: 0,
			},
			stroke: {
			  colors: undefined
			}
		  },
		colors:['#0F5EF7', '#00BC8B'],
        };

        var chart = new ApexCharts(document.querySelector("#chart42"), options);
        chart.render();
	
	
	
	
	 new ApexCharts(document.querySelector("#chart-5"), {
        series: [{ name: "Series 1", data: [20, 100, 40, 30, 50, 80, 33] }],
        chart: { height: 300, type: "radar" },
        dataLabels: { enabled: !0 },
        plotOptions: { radar: { size: 140, polygons: { strokeColors: "#e9e9e9", fill: { colors: ["#f8f8f8", "#fff"] } } } },
        colors: ["#6993ff"],
        markers: { size: 4, colors: ["#fff"], strokeColor: "#6993ff", strokeWidth: 2 },
        tooltip: {
            y: {
                formatter: function (e) {
                    return e;
                },
            },
        },
        xaxis: { categories: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"] },
        yaxis: {
            tickAmount: 7,
            labels: {
                formatter: function (e, t) {
                    return t % 2 == 0 ? e : "";
                },
            },
        },
    }).render();
	
	
    if ($("#e_chart_9").length > 0)
    {
        var e = echarts.init(document.getElementById("e_chart_9"));
        e.setOption({
            color: ["#8950fc", "#f64e60"],
            tooltip: {
                show: !0,
                trigger: "axis",
                backgroundColor: "#fff",
                borderRadius: 6,
                padding: 6,
                axisPointer: { lineStyle: { width: 0 } },
                textStyle: { color: "#324148", fontFamily: '-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji"', fontSize: 12 },
            },
            grid: { top: "3%", left: "3%", right: "3%", bottom: "3%", containLabel: !0 },
            xAxis: { type: "category", boundaryGap: !1, data: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12"], axisLine: { show: !1 }, axisTick: { show: !1 }, axisLabel: { textStyle: { color: "#5e7d8a" } } },
            yAxis: { type: "value", axisLine: { show: !1 }, axisTick: { show: !1 }, axisLabel: { textStyle: { color: "#5e7d8a" } }, splitLine: { lineStyle: { color: "#eaecec" } } },
            series: [
                { name: "1", type: "bar", stack: "st1", barMaxWidth: 10, data: [32, 33, 30, 33, 39, 33, 32, 32, 33, 30, 33, 39], itemStyle: { normal: { barBorderRadius: [50, 50, 0, 0] } } },
                { name: "2", type: "bar", stack: "st1", barMaxWidth: 10, data: [-12, -13, -10, -13, -11, -23, -21, -12, -13, -10, -13, -20], itemStyle: { normal: { barBorderRadius: [0, 0, 50, 50] } } },
            ],
        }),
            e.resize();
    }
    if ($("#e_chart_5").length > 0) {
        var t = echarts.init(document.getElementById("e_chart_5"));
        t.setOption({
            color: ["#8950fc", "#6993ff", "#f64e60", "#ffa800", "#1bc5bd"],
            tooltip: {
                show: !0,
                trigger: "axis",
                backgroundColor: "#fff",
                borderRadius: 6,
                padding: 6,
                axisPointer: { lineStyle: { width: 0 } },
                textStyle: { color: "#324148", fontFamily: '-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji"', fontSize: 12 },
            },
            grid: { top: "3%", left: "3%", right: "3%", bottom: "3%", containLabel: !0 },
            xAxis: [{ type: "category", data: ["Female", "Male"], axisLine: { show: !1 }, axisTick: { show: !1 }, axisLabel: { textStyle: { color: "#5e7d8a" } } }],
            yAxis: [{ type: "value", axisLine: { show: !1 }, axisTick: { show: !1 }, axisLabel: { textStyle: { color: "#5e7d8a" } }, splitLine: { lineStyle: { color: "#eaecec" } } }],
            series: [
                { name: "1", type: "bar", barMaxWidth: 18, data: [320, 332], barGap: "70%" },
                { name: "2", type: "bar", barMaxWidth: 18, data: [120, 132], barGap: "70%" },
                { name: "3", type: "bar", barMaxWidth: 18, data: [220, 182], barGap: "70%" },
                { name: "4", type: "bar", barMaxWidth: 18, data: [150, 232], barGap: "70%" },
                { name: "5", type: "bar", barMaxWidth: 18, data: [170, 222], barGap: "70%" },
            ],
        }),
            t.resize();
    }
	
	
	
	
	
	//dashboard_daterangepicker
	
	if(0!==$("#dashboard_daterangepicker").length) {
		var n=$("#dashboard_daterangepicker"),
		e=moment(),
		t=moment();
		n.daterangepicker( {
			startDate:e, endDate:t, opens:"left", ranges: {
				Today: [moment(), moment()], Yesterday: [moment().subtract(1, "days"), moment().subtract(1, "days")], "Last 7 Days": [moment().subtract(6, "days"), moment()], "Last 30 Days": [moment().subtract(29, "days"), moment()], "This Month": [moment().startOf("month"), moment().endOf("month")], "Last Month": [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")]
			}
		}
		, a),
		a(e, t, "")
	}
	function a(e, t, a) {
		var r="",
		o="";
		t-e<100||"Today"==a?(r="Today:", o=e.format("MMM D")): "Yesterday"==a?(r="Yesterday:", o=e.format("MMM D")): o=e.format("MMM D")+" - "+t.format("MMM D"), n.find(".subheader_daterange-date").html(o), n.find(".subheader_daterange-title").html(r)
	}
	
	
	
	
	
}); // End of use strict



                


