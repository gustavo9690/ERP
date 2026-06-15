import { CommonModule } from '@angular/common';
import { Component, EventEmitter, Input, Output } from '@angular/core';
import { Tab, TabsComponent } from '../tabs/tabs.component';

@Component({
  selector: 'app-page-header',
  standalone: true,
  imports: [CommonModule,TabsComponent ],
  templateUrl: './page-header.component.html',
  styleUrls: ['./page-header.component.scss']
})
export class PageHeaderComponent {

  @Input() title: string = '';
  @Input() subtitle: string = '';

  @Input() tabs: Tab[] = [];
  @Input() activeTab: string = '';

  @Output() tabChange = new EventEmitter<string>();

  onTabChange(tab: string): void {
    this.tabChange.emit(tab);
  }

}