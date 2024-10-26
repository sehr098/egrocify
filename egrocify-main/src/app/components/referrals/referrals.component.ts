import { Component } from '@angular/core';
import {MatTableDataSource, MatTableModule} from '@angular/material/table';
import {MatIconModule} from '@angular/material/icon';
import { ColumnDefinition, SharedTableComponent, TableAction, TableData } from '../../shared/shared-table/shared-table.component';

@Component({
  selector: 'app-referrals',
  standalone: true,
  imports: [MatTableModule,MatIconModule,SharedTableComponent],
  templateUrl: './referrals.component.html',
  styleUrl: './referrals.component.scss'
})
export class ReferralsComponent {
  companyData: TableData[] = [
    { id: 1, Name: 'Test Company', Email: 'test-company', Package: 1, RegisterAt: 1},
    { id: 2, Name: 'Another Company', Email: 'another-company', Package: 2, RegisterAt: 5},
    { id: 3, Name: 'Test Company', Email: 'test-company', Package: 1, RegisterAt: 1},
    { id: 4, Name: 'Another Company', Email: 'another-company', Package: 2, RegisterAt: 5},
    { id: 5, Name: 'Test Company', Email: 'test-company', Package: 1, RegisterAt: 1},
    { id: 6, Name: 'Another Company', Email: 'another-company', Package: 2, RegisterAt: 5},
    { id: 7, Name: 'Test Company', Email: 'test-company', Package: 1, RegisterAt: 1},
    { id: 8, Name: 'Another Company', Email: 'another-company', Package: 2, RegisterAt: 5},
    { id: 9, Name: 'Test Company', Email: 'test-company', Package: 1, RegisterAt: 1},
    { id: 10, Name: 'Another Company', Email: 'another-company', Package: 2, RegisterAt: 5},
    { id: 11, Name: 'Another Company', Email: 'another-company', Package: 2, RegisterAt: 5},
    // Add more data as needed
  ];

  columns: ColumnDefinition[] = [
    { key: 'Name', header: 'Name' },
    { key: 'Email', header: 'Email', backgroundColor: 'orange', textColor: 'black', customdiv: true },
    { key: 'Package', header: 'Package', backgroundColor: 'purple', textColor: 'black', customdiv: true },
    { key: 'RegisterAt', header: 'Register At', backgroundColor: 'lightblue', textColor: 'black', customdiv: true },
  ];

  actions: TableAction[] = [
    { label: 'Action 1', action: this.action1 },
    { label: 'Action 2', action: this.action2 },
    { label: 'Action 3', action: this.action3 },
    { label: 'Action 4', action: this.action4 },
  ];

  action1(element: TableData) {
    console.log('Action 1 clicked', element);
  }

  action2(element: TableData) {
    console.log('Action 2 clicked', element);
  }

  action3(element: TableData) {
    console.log('Action 3 clicked', element);
  }

  action4(element: TableData) {
    console.log('Action 4 clicked', element);
  }
}
