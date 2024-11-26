<div x-data="ap" x-init="updateChart()">
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
        {{-- @post-created.window="data = $event.detail.data" --}}
        <div >
            <span>filtro de la informacion</span>
            <x-date-piker />
            <canvas id="myChart" x-ref="canvas"></canvas>
        </div>

    </div>
</div>
@assets
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endassets

@script
<script>
    // document.addEventListener('alpine:init', () => {
        Alpine.data('ap', () => { 
            return {
                SIabordo: [],
                NOabordo: [],
                label: [],

                init(){
                    console.log("WW")
                    this.$watch('label', (e) => {
                        console.log(e);
                       
                    });
                    this.createChart()
                },

                createChart(){
                  const  data = {
                            labels: this.label,
                            datasets: [{
                            label: '# de Abordo',
                            data: this.SIabordo,
                            borderWidth: 1
                            },
                            {
                            label: '# de No abordo',
                            data: this.NOabordo,
                            borderWidth: 1
                            }]
                        };

                    const config = {
                        type: 'bar',
                        data: data,
                        options: {
                            scales: {
                            y: {
                                sbeginAtZero: true,
                                // stacked: true  
                                }
                            },
                            interaction: {
                            // Overrides the global setting
                                mode: 'index'
                            }
                        }

                    };

                     const myChart = new Chart(
                        this.$refs.canvas,
                        config
                    );
                },

                updateChart(){
                    this.$el.addEventListener('post-created', (event) => {
                        // console.log(event.detail.data);
                        
                        this.transform(event.detail.data)
                        console.log('se resive evento de otro listener xd ');
                        
                    })
                },

                transform(abordo){
                    const group = abordo.reduce((accumulator, item)  => {
                    const origen = item.origen

                    if(!accumulator[origen]){
                        accumulator[origen] = {origen: origen, abordo: 0, no_abordo: 0}
                    }

                    if(item.Abordo === "NO ABORODO"){
                        accumulator[origen].no_abordo += item.cantidad
                    }else{
                            accumulator[origen].abordo += item.cantidad
                        }
                        return accumulator
                    }, {})

                    const output = Object.values(group);
                    console.log(output);
                    

                    this.label = output.map(data => data.origen)
                    this.SIabordo = output.map(data => data.abordo)
                    this.NOabordo = output.map(data => data.no_abordo)
                }

            }  
        })
    // });


     //const ctx = document.getElementById('myChart');
    // let abordo = $wire.abordo 

    Livewire.on('post-created', ({ data }) => {
        //console.log(data);
       // create(data)
})


   
    
    function create(abordo){
        const group = abordo.reduce((accumulator, item)  => {
        const origen = item.origen

        if(!accumulator[origen]){
            accumulator[origen] = {origen: origen, abordo: 0, no_abordo: 0}
        }

        if(item.Abordo === "NO ABORODO"){
            accumulator[origen].no_abordo += item.cantidad
        }else{
            accumulator[origen].abordo += item.cantidad
        }
        return accumulator
    }, {})

    const output = Object.values(group);

    const label = output.map(data => data.origen)
    const SIabordo = output.map(data => data.abordo)
    const NOabordo = output.map(data => data.no_abordo)

    console.log(SIabordo);
    
    
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: label,
        datasets: [{
          label: '# de Abordo',
          data: SIabordo,
          borderWidth: 1
        },
        {
          label: '# de No abordo',
          data: NOabordo,
          borderWidth: 1
        }]
      },
      options: {
        scales: {
            // x: {
            //  stacked: true   
            // },
          y: {
            beginAtZero: true,
            // stacked: true  
          }
        }
      }
    });
    }

   
</script>
@endscript