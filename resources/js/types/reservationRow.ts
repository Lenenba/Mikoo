import { h } from 'vue'
import DropdownAction from '@/components/DataTableDropDown.vue'
import type { ColumnDef } from '@tanstack/vue-table'
import { Button } from '@/components/ui/button'
import { ArrowUpDown } from 'lucide-vue-next'

// Define the ReservationRow type matching the service output
interface ReservationRow {
    id: number
    status: string
    notes: string | null
    is_recurring: boolean
    recurrence_rule: string | null
    start_date: string | null
    end_date: string | null
    start_time: string | null
    end_time: string | null
    parent: {
        id: number
        name: string
        email: string
        phone: string
        photo_url: string
    }
    babysitter: {
        id: number
        name: string
        email: string
        phone: string
        photo_url: string
    }
}

export const columns: ColumnDef<ReservationRow>[] = [
    {
        id: 'parent',
        header: ({ column }) =>
            h(Button, {
                variant: 'ghost',
                onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
            }, () => [
                'Parent',
                h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })
            ]),
        cell: ({ row }) => {
            const parent = row.original.parent
            return h('div', { class: 'flex items-center gap-2' }, [
                h('img', {
                    src: parent.photo_url,
                    alt: parent.name,
                    class: 'h-8 w-8 rounded-full object-cover'
                }),
                h('div', { class: 'flex flex-col' }, [
                    h('span', { class: 'font-medium' }, parent.name),
                    h('span', { class: 'text-xs text-muted-foreground' }, parent.email),
                    h('span', { class: 'text-xs text-muted-foreground' }, parent.phone)
                ])
            ])
        }
    },
    {
        id: 'babysitter',
        header: ({ column }) =>
            h(Button, {
                variant: 'ghost',
                onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
            }, () => [
                'Babysitter',
                h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })
            ]),
        cell: ({ row }) => {
            const b = row.original.babysitter
            return h('div', { class: 'flex items-center gap-2' }, [
                h('img', {
                    src: b.photo_url,
                    alt: b.name,
                    class: 'h-8 w-8 rounded-full object-cover'
                }),
                h('div', { class: 'flex flex-col' }, [
                    h('span', { class: 'font-medium' }, b.name),
                    h('span', { class: 'text-xs text-muted-foreground' }, b.email),
                    h('span', { class: 'text-xs text-muted-foreground' }, b.phone)
                ])
            ])
        }
    },
    {
        accessorKey: 'start_date',
        header: 'Start',
        cell: ({ row }) => h('span', null, row.getValue('start_date') || '-')
    },
    {
        accessorKey: 'end_date',
        header: 'End',
        cell: ({ row }) => h('span', null, row.getValue('end_date') || '-')
    },
    {
        accessorKey: 'status',
        header: 'Status',
        cell: ({ row }) => {
            const status = row.getValue('status') as string
            // Choix de la couleur selon le statut
            const colorClasses: Record<string, string> = {
                pending: 'text-yellow-700 bg-yellow-100',
                confirmed: 'text-green-700  bg-green-100',
                canceled: 'text-red-700    bg-red-100',
            }
            const classes = colorClasses[status.toLowerCase()] ?? 'text-gray-700 bg-gray-100'

            return h(
                'span',
                {
                    class: `inline-block px-2 py-1 rounded-full text-sm font-medium capitalize ${classes}`
                },
                status
            )
        },
    },
    {
        id: 'actions',
        enableHiding: false,
        header: 'Actions',
        cell: ({ row }) => h('div', { class: 'relative' }, h(DropdownAction, { reservation: row.original }))
    }
]
