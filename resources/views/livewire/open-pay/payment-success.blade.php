<div
x-data  
  x-init="
        const pending = @js($pending);

        if (pending) {
            console.log('iniciando recarga');

            setTimeout(() => {
                window.location.reload();
            }, 50000);
        }
    "
>
    <x-open-pay.payment-success :pasajero="$pasajero" />
</div>