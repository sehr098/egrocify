import { Component } from '@angular/core';
import {MatTableDataSource, MatTableModule} from '@angular/material/table';
import {MatIconModule} from '@angular/material/icon';
import { ColumnDefinition, SharedTableComponent, TableAction, TableData } from '../../../../shared/shared-table/shared-table.component';

@Component({
  selector: 'app-product-list',
  standalone: true,
  imports: [MatTableModule,MatIconModule,SharedTableComponent],
  templateUrl: './product-list.component.html',
  styleUrl: './product-list.component.scss'
})
export class ProductListComponent {
  companyData: TableData[] = [
    { id: 1, Name: 'Test Company', Package: 'test-company', Brand: 1, Price: 1, Active: 1, ROI: 1, ROIDays: 7},
    { id: 2, Name: 'Another Company', Package: 'another-company', Brand: 2, Price: 5, Active: 1, ROI: 1, ROIDays: 7},
    { id: 3, Name: 'Test Company', Package: 'test-company', Brand: 1, Price: 1, Active: 1, ROI: 1, ROIDays: 7},
    { id: 4, Name: 'Another Company', Package: 'another-company', Brand: 2, Price: 5, Active: 1, ROI: 1, ROIDays: 7},
    { id: 5, Name: 'Test Company', Package: 'test-company', Brand: 1, Price: 1, Active: 1, ROI: 1, ROIDays: 7},
    { id: 6, Name: 'Another Company', Package: 'another-company', Brand: 2, Price: 5, Active: 1, ROI: 1, ROIDays: 7},
    { id: 7, Name: 'Test Company', Package: 'test-company', Brand: 1, Price: 1, Active: 1, ROI: 1, ROIDays: 7},
    { id: 8, Name: 'Another Company', Package: 'another-company', Brand: 2, Price: 5, Active: 1, ROI: 1, ROIDays: 7},
    { id: 9, Name: 'Test Company', Package: 'test-company', Brand: 1, Price: 1, Active: 1, ROI: 1, ROIDays: 7},
    { id: 10, Name: 'Another Company', Package: 'another-company', Brand: 2, Price: 5, Active: 1, ROI: 1, ROIDays: 7},
    { id: 11, Name: 'Another Company', Package: 'another-company', Brand: 2, Price: 5, Active: 1, ROI: 1, ROIDays: 7},
    // Add more data as needed
  ];

  columns: ColumnDefinition[] = [
    { key: 'Name', header: 'Name' },
    { key: 'Package', header: 'Package', backgroundColor: 'orange', textColor: 'black', customdiv: true },
    { key: 'Brand', header: 'Brand', backgroundColor: 'purple', textColor: 'black', customdiv: true },
    { key: 'Price', header: 'Price', backgroundColor: 'lightblue', textColor: 'black', customdiv: true },
    { key: 'Active', header: 'Active' , backgroundColor: 'peach', textColor: 'black', customdiv: false },
    { key: 'ROI', header: 'ROI' , backgroundColor: 'brown', textColor: 'black', customdiv: false },
    { key: 'ROIDays', header: 'ROI Days' , backgroundColor: 'lightgreen', textColor: 'black', customdiv: false },
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
