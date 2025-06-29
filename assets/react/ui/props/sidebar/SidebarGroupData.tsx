import {GroupSettings} from "./GroupSettings";
import {GroupItem} from "./GroupItem";

export interface SidebarGroupData {
    'group-settings': GroupSettings[];
    'group-item'?: GroupItem[];
}