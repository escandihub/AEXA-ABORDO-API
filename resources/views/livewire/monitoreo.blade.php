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
           
             <canvas id="myChart2" x-ref="canvas2"></canvas> 
             <canvas id="myChart3" x-ref="canvas3"></canvas> 
        </div>

    </div>
</div>
@assets
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endassets

@script
<script>
    let chart2 = null;
    let chart3 = null;
        Alpine.data('ap', () => { 
            return {
                SIabordo: [],
                NOabordo: [],
                hora: [],
                label: ["A", "B"],
                chart: null,
                charts : [],
                selected: null,
                abordo: null,

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
                            borderWidth: 1
                            }]
                        };

                    const config = {
                        type: 'bar',
                        data: data,
                        options: {
                            scales: {
                                // x: {
                                // stacked: true   
                                // },
                            y: {
                                sbeginAtZero: true,
                                // stacked: true  
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
                            labels: [],
                            datasets: [{
                            label: ' Si abordo',
                            data: [],
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

                    const output = Object.values(group);
                    console.log(output);
                    this.abordo = output
                    // this.renderChart(output, chart2)
                    this.label = output.map(data => data.origen)
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