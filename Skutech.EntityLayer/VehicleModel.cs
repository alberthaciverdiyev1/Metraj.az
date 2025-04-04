using System.ComponentModel.DataAnnotations;
using Microsoft.EntityFrameworkCore;

namespace Skutech.EntityLayer;

public class VehicleModel
{   [Key]
    public int Id { get; set; }
    [Required]
    public string Name { get; set; }
    public int BrandId { get; set; }
    public Brand? Brand { get; set; }
    
}