import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
import Flux from '../../vendor/livewire/flux/flux.esm.js';

Alpine.plugin(Flux);

Livewire.start();
