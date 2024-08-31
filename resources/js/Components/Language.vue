<script setup>
import { ref, onMounted, watch } from 'vue';
import Dropdown from 'primevue/dropdown';
import { loadLanguageAsync, getActiveLanguage } from 'laravel-vue-i18n';
import { usePage, router } from '@inertiajs/vue3'

const page = usePage();
const location = page.props.ziggy.location;
const selectedLang = ref(null);
const lang = ref(getActiveLanguage());
const langs = ref([
    { name: 'Português', code: 'pt', country: 'BR' },
    { name: 'English', code: 'en', country: 'US' },
]);

onMounted(() => {
    const savedLang = localStorage.getItem('currentLang') || 'pt';
    if (savedLang) {
        selectedLang.value = langs.value.find(l => l.code === savedLang);
        changeLanguage(savedLang);
    }
});
watch(selectedLang, async (newLang) => {
    if (newLang) {
        await changeLanguage(newLang.code);
    }
});

const changeLanguage = async (newLangCode) => {
    const newLang = langs.value.find(lang => lang.code === newLangCode);
    await loadLanguageAsync(newLang.code);
    localStorage.setItem('currentLang', newLang.code);
    lang.value = newLang.code;
};

const refreshLang = () => {
    router.visit(location)
}
</script>
<template>
    <div class="card flex justify-content-center">
        <Dropdown v-model="selectedLang" :options="langs" optionLabel="name" placeholder="Select a Language"
            class="w-full md:w-14rem" @change="refreshLang">
            <template #value="slotProps">
                <div v-if="slotProps.value" class="flex align-items-center">
                    <img :src="`http://purecatamphetamine.github.io/country-flag-icons/3x2/${slotProps.value.country}.svg`"
                        :alt="slotProps.value.name" class="mr-2" style="width: 18px" />
                    <div>{{ slotProps.value.name }}</div>
                </div>
                <span v-else>
                    {{ slotProps.placeholder }}
                </span>
            </template>
            <template #option="slotProps">
                <div class="flex align-items-center">
                    <img :src="`http://purecatamphetamine.github.io/country-flag-icons/3x2/${slotProps.option.country}.svg`"
                        :alt="slotProps.option.name" class="mr-2" style="width: 18px" />
                    <div>{{ slotProps.option.name }}</div>
                </div>
            </template>
        </Dropdown>
    </div>
</template>