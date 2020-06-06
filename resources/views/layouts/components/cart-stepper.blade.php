<div class="mb-2 py-2">
    <div id="stepper2" class="bs-stepper border rounded">
        <div class="bs-stepper-header" role="tablist">

            <div class="step">
                <button type="button" class="step-trigger" role="tab" id="stepper1">
                    <span class="bs-stepper-circle {{ $step === 'cart' ? 'bg-primary' : '' }}">
                        <span class="fa fa-shopping-bag" aria-hidden="true"></span>
                    </span>
                    <span class="bs-stepper-label {{ $step === 'cart' ? 'text-primary' : '' }}">Produtos</span>
                </button>
            </div>

            <div class="bs-stepper-line"></div>

            <div class="step">
                <button type="button" class="step-trigger" role="tab" id="stepper2">
                    <span class="bs-stepper-circle {{ $step === 'user' ? 'bg-primary' : '' }}">
                        <span class="fas fa-map-marked" aria-hidden="true"></span>
                    </span>
                    <span class="bs-stepper-label {{ $step === 'user' ? 'text-primary' : '' }}">Dados pessoais</span>
                </button>
            </div>

            <div class="bs-stepper-line"></div>

            <div class="step">
                <button type="button" class="step-trigger" role="tab" id="stepper2">
                    <span class="bs-stepper-circle {{ $step === 'address' ? 'bg-primary' : '' }}">
                        <span class="fas fa-map-marked" aria-hidden="true"></span>
                    </span>
                    <span class="bs-stepper-label {{ $step === 'address' ? 'text-primary' : '' }}">Endereço</span>
                </button>
            </div>

            <div class="bs-stepper-line"></div>

            <div class="step">
                <button type="button" class="step-trigger" role="tab" id="stepper3">
                    <span class="bs-stepper-circle {{ $step === 'finish' ? 'bg-primary' : '' }}">
                        <span class="fas fa-save" aria-hidden="true"></span>
                    </span>
                    <span class="bs-stepper-label {{ $step === 'finish' ? 'text-primary' : '' }}">Finalizar</span>
                </button>
            </div>

        </div>
    </div>
</div>
