<div x-data="ap">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Monitoreo') }}
        </h2>
    </x-slot>

    <div wire:loading>
        <div class="fixed inset-0  top-0 left-0 z-50 mx-auto w-screen h-screen flex items-center justify-center"
            style="background: rgba(0, 0, 0, 0.3);">
            <div class="flex justify-center items-center space-x-1 text-sm text-gray-700">
                <span class="loader"></span>
            </div>
        </div>
    </div>
    <div class="max-w-7xl mx-auto overflow-x-auto shadow-md sm:rounded-lg">
         
        <div @post-created.window="setChart($event.detail.data)">
            <canvas id="chart2text" x-ref="canvas2text"></canvas>
            <span>filtro de la informacion</span>
            <x-date-piker />
            <canvas id="myChart" x-ref="canvas"></canvas>
            <div >
                <select name="terminales" x-model="selected" x-on:change="onTerminal">
                    <template x-for="(name, index) in label">
                        <option x-bind:value="index" x-text="name"></option>
                    </template>
                </select>
            </div>
           
            <h2> Grafica de uso por hora </h2>
             <canvas id="myChart2" x-ref="canvas2"></canvas> 
             <h2> Grafica de uso por fecha </h2>
             <canvas id="myChart3" x-ref="canvas3"></canvas> 
        </div>

    </div>
</div>
@assets
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
@endassets

@script
<script>
    let chart2 = null;
    let chart3 = null;
    let chart2text = null;
        Alpine.data('ap', () => { 
            return {
                SIabordo: [],
                NOabordo: [],
                hora: [],
                label: ["A", "B"],
                chart: null,
                charts : [],
                selected: null,
                abordo: [],

                init(){
                    console.log("WW")
                    // this.$watch('label', (e) => {
                    //     console.log(e);
                    // });
                    this.createChart()
                },
                onTerminal(e){
                    console.log(e.target.value)
                    this.renderChart(e.target.value, chart2)
                },
                setChart(data){
                  this.transform(data)
                    this.chart.data.datasets.forEach((dataset, index) => {
                    dataset.data = index === 0 ? this.SIabordo :  this.NOabordo
                    });
                    //    this.chart.data.datasets[1].data = this.NOabordo
                     this.chart.data.labels = this.label 
                     //this.chart.update()
                    console.log('se resive evento de otro listener xd ');
                },

                createChart(){
                  const  data = {
                            labels: [],
                            datasets: [{
                            label: '# de Abordo',
                            data: [],
                            borderWidth: 1
                            },
                            {
                            label: '# de No abordo',
                            data: [],
                            borderWidth: 1,
                            datalabels: {
                            color: 'white'
                        },
                            }]
                        };

                    const config = {
                        type: 'bar',
                        data: data,
                        plugins: [ChartDataLabels],
                        options: {
                            scales: {
                                x: {
                                  stacked: true, 
                                 },
                            y: {
                                sbeginAtZero: true,
                                stacked: true,  
                                labels: {
                                render: 'percentage'
                            }
                                }
                            },
                        }
                    };
                    const  data2 = {
                            labels: [],
                            datasets: [{
                            label: 'Arr Si abordo',
                            data: [],
                            borderWidth: 1
                            },
                            {
                            label: '# arr de No abordo',
                            data: [],
                            borderWidth: 1
                            }]
                        };
                    const config2 = {
                        type: 'line',
                        data: data2,
                        options: {}

                    };
                    const  data3 = {
                            labels: ["a"],
                            datasets: [{
                            label: ' Si abordo',
                            data: [1],
                            borderWidth: 1
                            },
                            {
                            label: 'No abordo',
                            data: [],
                            borderWidth: 1
                            }]
                        };
                    const config3 = {
                        type: 'line',
                        data: data3,
                        options: {}

                    };

                     this.chart = new Chart(
                        this.$refs.canvas,
                        config
                    );
                    chart2 = new Chart(
                        this.$refs.canvas2,
                        config2
                    );
                    chart3 = new Chart(
                        this.$refs.canvas3,
                        config3
                    );

                    const dataText = {
                            labels: ["A","B","C","34E"],
                            datasets: [{
                            label: '# de Abordo',
                            data: [2,3,4,5],
                            borderWidth: 1
                            },
                            {
                            label: '# de No abordo',
                            data: [2,3,4,5],
                            borderWidth: 1
                            }]
                        };
                    const configT = {
                        type: 'bar',
                        data: dataText,
                        options: {
                            scales: {
                                 x: {
                                 stacked: true,
                                
                                    title: {
                                        display: true,
                                        text: 'Días de la Semana'
                                    },
                                    // labels: this.label.
                                 },
                            y: {
                                //sbeginAtZero: true,
                                stacked: true,
                                title: {
                                    display: true,
                                    text: "Numero de pasajeros"
                                }
                                },
                            }
                        } 
                    };
                    chart2text = new Chart(
                        this.$refs.canvas2text,
                        configT
                    );
                },

                transform(abordo){
                    const group = abordo.reduce((accumulator, item)  => {
                    const origen = item.origen
                    const time = `${item.hora}:${item.minutos}`
                    const date = item.fecha_salida

                    if(!accumulator[origen]){
                        accumulator[origen] = {origen: origen, abordo: 0, no_abordo: 0, usagebyHour: {}, usagebyDate: {}}
                    }

                    if(!accumulator[origen].usagebyHour[time]){
                            accumulator[origen].usagebyHour[time] = {
                            abordo: 0,
                            no_abordo: 0
                            }
                        }
                    if(!accumulator[origen].usagebyDate[date]){
                            accumulator[origen].usagebyDate[date] = {
                            abordo: 0,
                            no_abordo: 0
                            }
                        }

                    if(item.Abordo === "NO ABORODO"){
                        accumulator[origen].usagebyHour[time].no_abordo += item.cantidad
                        accumulator[origen].no_abordo += item.cantidad
                        accumulator[origen].usagebyDate[date].no_abordo += item.cantidad
                    }else{
                        accumulator[origen].abordo += item.cantidad
                        accumulator[origen].usagebyHour[time].abordo += item.cantidad
                        accumulator[origen].usagebyDate[date].abordo += item.cantidad
                        }
                        return accumulator
                    }, {});

                    const pre = Object.values(group);
                    
                    const output = pre.map(abordo => ({
                        ...abordo,
                        ["porcentaje"]: Math.trunc(abordo['abordo'] / (abordo['abordo'] + abordo['no_abordo']) * 100) 
                    }));
                    // console.log(addPercentaje);
                    
                    this.abordo = output
                    // this.renderChart(output, chart2)
                    //console.log(this.abordo);
                    // map(v => `${v} \n ${this.abordo[1].porcentaje}%`)
                    this.label = output.map(data =>  `${data.origen} \n ${data.porcentaje}%`)
                    this.SIabordo = output.map(data => data.abordo)
                    this.NOabordo = output.map(data => data.no_abordo)
                    return 0;
                },

                setNewChart(abordo){
                    const times = Object.keys(abordo[0].usagebyHour) // labels
                    const si_abordo = Object.entries(abordo[0].usagebyHour).map(([key, value]) => value.abordo) 
                    const no_abordo = Object.entries(abordo[0].usagebyHour).map(([key, value]) => value.no_abordo) 
                    // const no_abordo = abordo[0].usagebyHour.map(data => no_abordo)
                    console.log(times)
                    console.log(si_abordo)
                    console.log(no_abordo)
                    this.chart2.data.labels  = times
                    this.chart2.data.datasets[0].data = si_abordo
                    this.chart2.data.datasets[1].data = no_abordo
                },
                renderChart(origin, chart){
                    console.log("update()");
                    
                    
                    const key = origin//this.abordo.findIndex((item) => item.origen === origin)
                    // por horario
                    const times = Object.keys(this.abordo[key].usagebyHour) // labels
                    const si_abordo = Object.entries(this.abordo[key].usagebyHour).map(([key, value]) => value.abordo) 
                    const no_abordo = Object.entries(this.abordo[key].usagebyHour).map(([key, value]) => value.no_abordo)
                    // por fecha_salida
                    const fecha_label = Object.keys(this.abordo[key].usagebyDate) // labels
                    const fecha_si_abordo = Object.entries(this.abordo[key].usagebyDate).map(([key, value]) => value.abordo) 
                    const fecha_no_abordo = Object.entries(this.abordo[key].usagebyDate).map(([key, value]) => value.no_abordo)

                    chart3.data.labels = fecha_label
                    chart3.data.datasets[0].data = fecha_si_abordo
                    chart3.data.datasets[1].data = fecha_no_abordo


                    chart.data.labels  = times
                    chart.data.datasets[0].label = `${this.abordo[origin].origen} Abordo`
                    chart.data.datasets[0].data = si_abordo
                    chart.data.datasets[1].label = `${this.abordo[origin].origen} No Abordo`
                    chart.data.datasets[1].data = no_abordo
                    chart.update()
                    chart3.update()
                },

            }  
        })

</script>
@endscript