export interface Menu {
    name: string;
    icon: string;
    url: string;
    role: boolean;
    seperator?: boolean;
    subItems?: Menu[];
}
