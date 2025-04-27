<script setup lang="ts" generic="T extends Record<string, any>">
import { computed } from 'vue'
import {
    Table,
    TableBody,
    TableCaption,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table' // Assurez-vous que le chemin est correct

// Définition du type pour une colonne
interface ColumnDefinition<T> {
    key: keyof T | string; // La clé dans l'objet de données (peut être une clé imbriquée via string 'a.b.c')
    label: string; // L'étiquette à afficher dans l'en-tête
    headerClass?: string; // Classes CSS optionnelles pour l'en-tête
    cellClass?: string | ((item: T) => string); // Classes CSS optionnelles pour la cellule (peut être une fonction)
    formatter?: (value: any, item: T) => string; // Fonction optionnelle pour formater la valeur de la cellule
}

// Définition des props
const props = withDefaults(defineProps<{
    items: T[]; // Le tableau d'objets de données
    columns: ColumnDefinition<T>[]; // La configuration des colonnes
    caption?: string; // La légende optionnelle de la table
    rowKey: keyof T | ((item: T) => string | number); // La clé unique pour chaque ligne (propriété ou fonction)
}>(), {
    caption: '', // Valeur par défaut pour la légende
})

// Fonction pour obtenir la valeur d'une cellule, gère les clés imbriquées (ex: 'user.name')
const getCellValue = (item: T, key: string): any => {
    return key.split('.').reduce((acc, part) => acc && acc[part], item);
}

// Fonction pour déterminer la clé unique de la ligne
const getRowKey = (item: T): string | number => {
    if (typeof props.rowKey === 'function') {
        return props.rowKey(item);
    }
    // Assurez-vous que la clé existe avant d'y accéder
    if (props.rowKey in item && item[props.rowKey] !== undefined && item[props.rowKey] !== null) {
        return item[props.rowKey] as string | number;
    }
    // Fallback très basique (non idéal si les données changent souvent)
    console.warn(`[GenericTable] Impossible de trouver une clé unique pour la ligne en utilisant la propriété '${String(props.rowKey)}'. L'index sera utilisé comme fallback.`);
    // Trouver l'index peut être moins performant ou impossible ici sans passer l'index.
    // Si une clé unique est cruciale, rendez rowKey strictement obligatoire et validez sa présence.
    // Pour cet exemple, on pourrait générer une clé faible, mais il vaut mieux exiger une bonne rowKey.
    // Retournons une représentation stringifiée partielle comme fallback très faible.
    return JSON.stringify(item).substring(0, 50); // Pas idéal !
}

// Fonction pour obtenir les classes de cellule
const getCellClass = (column: ColumnDefinition<T>, item: T): string => {
    if (typeof column.cellClass === 'function') {
        return column.cellClass(item);
    }
    return column.cellClass ?? '';
}

</script>

<template>
    <Table>
        <TableCaption v-if="caption">{{ caption }}</TableCaption>
        <TableHeader>
            <TableRow>
                <TableHead v-for="column in columns" :key="String(column.key)" :class="column.headerClass">
                    {{ column.label }}
                </TableHead>
            </TableRow>
        </TableHeader>
        <TableBody>
            <template v-if="items.length > 0">
                <TableRow v-for="(item) in items" :key="getRowKey(item)">
                    <TableCell v-for="column in columns" :key="String(column.key)" :class="getCellClass(column, item)">
                        {{
                            column.formatter
                                ? column.formatter(getCellValue(item, String(column.key)), item)
                                : getCellValue(item, String(column.key))
                        }}
                    </TableCell>
                </TableRow>
            </template>
            <template v-else>
                <TableRow>
                    <TableCell :colspan="columns.length" class="text-center h-24">
                        Aucune donnée disponible.
                    </TableCell>
                </TableRow>
            </template>
        </TableBody>
    </Table>
</template>
