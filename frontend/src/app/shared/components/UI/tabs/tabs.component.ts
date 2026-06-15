import { CommonModule } from '@angular/common';
import { Component, EventEmitter, Input, Output } from '@angular/core';

export interface Tab {
  key: string;
  label: string;
}

@Component({
  selector: 'app-tabs',
  standalone: true,
  imports: [CommonModule],
  templateUrl: './tabs.component.html',
  styleUrls: ['./tabs.component.scss']
})
export class TabsComponent {

  @Input() tabs: Tab[] = [];
  @Input() activeTab: string = '';

  @Output() tabChange = new EventEmitter<string>();

  selectTab(tabKey: string): void {
    this.tabChange.emit(tabKey);
  }
}