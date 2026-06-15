import { CommonModule } from '@angular/common';
import { Component } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { PageHeaderComponent } from '../../../../../shared/components/UI/page-header/page-header.component';
import { Submodulos } from '../../components/submodulos/submodulos';
import { Opciones } from '../../components/opciones/opciones';
import { ModulosComponent } from '../../components/modulos/modulos.component';
import { Tab } from '../../../../../shared/components/UI/tabs/tabs.component';

@Component({
  selector: 'app-estructura-page',
  standalone: true,
  imports: [
    CommonModule,
    FormsModule,
    PageHeaderComponent,
    ModulosComponent,
    Submodulos,
    Opciones,
  ],
  templateUrl: './estructura-page.component.html',
  styleUrls: ['./estructura-page.component.scss']
})

export class EstructuraPageComponent {

  tab: string = 'modulos';

  tabs: Tab[] = [
    { key: 'modulos', label: 'Módulos' },
    { key: 'submodulos', label: 'Submódulos' },
    { key: 'opciones', label: 'Opciones' }
  ];

  changeTab(tab: string): void {
    this.tab = tab;
  }

}

