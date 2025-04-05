import "../css/app.css";
import "./bootstrap";
import "primeicons/primeicons.css";

import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { createApp, h } from "vue";
import { ZiggyVue } from "../../vendor/tightenco/ziggy";
import PrimeVue from "primevue/config";
import Aura from "@primeuix/themes/aura";
import Card from "primevue/card";
import Button from "primevue/button";
import Carousel from "primevue/carousel";
import Dialog from "primevue/dialog";
import InputText from "primevue/inputtext";
import { Message } from "primevue";
import Textarea from "primevue/textarea";
import MultiSelect from "primevue/multiselect";
import InputGroup from "primevue/inputgroup";
import InputGroupAddon from "primevue/inputgroupaddon";
import InputNumber from "primevue/inputnumber";
import ToastService from "primevue/toastservice";
import Toast from "primevue/toast";
import { Form } from "@primevue/forms";
import AutoComplete from "primevue/autocomplete";
import Dropdown from "primevue/dropdown";

const appName = import.meta.env.VITE_APP_NAME || "Laravel";

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue")
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(ToastService)
            .use(PrimeVue, {
                theme: {
                    preset: Aura,
                },
            })
            .component("Card", Card)
            .component("Button", Button)
            .component("Carousel", Carousel)
            .component("Dialog", Dialog)
            .component("InputText", InputText)
            .component("Message", Message)
            .component("Textarea", Textarea)
            .component("MultiSelect", MultiSelect)
            .component("InputGroup", InputGroup)
            .component("InputGroupAddon", InputGroupAddon)
            .component("InputNumber", InputNumber)
            .component("Toast", Toast)
            .component("Form", Form)
            .component("AutoComplete", AutoComplete)
            .component("Dropdown", Dropdown)
            .mount(el);
    },
    progress: {
        color: "#4B5563",
    },
});
