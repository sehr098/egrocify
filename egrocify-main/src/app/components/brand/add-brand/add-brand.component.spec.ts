import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AddBrandComponent } from './add-brand.component';

describe('AddBrandComponent', () => {
  let component: AddBrandComponent;
  let fixture: ComponentFixture<AddBrandComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [AddBrandComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(AddBrandComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
