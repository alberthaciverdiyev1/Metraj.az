using System.ComponentModel.DataAnnotations;

namespace Skutech.EntityLayer;

public class Vehicle
{
    [Key]
    public int Id { get; set; }
    
    [Required]
    public int BrandId { get; set; } 
    public Brand? Brand { get; set; } 
    [Required]
    public int VehicleModelId { get; set; }
    public VehicleModel? VehicleModel { get; set; } 
    [Required]
    public int ColorId { get; set; }
    public Color? Color { get; set; }

}