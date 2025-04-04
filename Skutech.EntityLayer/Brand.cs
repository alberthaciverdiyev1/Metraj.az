using System.ComponentModel.DataAnnotations;

namespace Skutech.EntityLayer;

public class Brand
{
    [Key]
    public int Id { get; set; }
    [Required]
    [MinLength(2)]
    [MaxLength(50)]
    public string Name { get; set; }
    public string? Image { get; set; }
}