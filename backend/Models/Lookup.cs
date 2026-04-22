using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;

namespace SagipPagkain.API.Models;

[Table("tblcategory")]
public class Category
{
    [Key]
    public int intCategoryId { get; set; }
    public string strCategory { get; set; } = string.Empty;
    public bool ysnActive { get; set; } = true;

    public ICollection<Item> Items { get; set; } = [];
}

[Table("tblunit")]
public class Unit
{
    [Key]
    public int intUnitId { get; set; }
    public string strUnit { get; set; } = string.Empty;
    public bool ysnActive { get; set; } = true;

    public ICollection<Item> Items { get; set; } = [];
}

[Table("tblitem")]
public class Item
{
    [Key]
    public int intItemId { get; set; }
    public int intCategoryId { get; set; }
    public int intUnitId { get; set; }
    public string strItem { get; set; } = string.Empty;
    public bool ysnActive { get; set; } = true;

    public Category? Category { get; set; }
    public Unit? Unit { get; set; }
}

[Table("tblpurpose")]
public class Purpose
{
    [Key]
    public int intPurposeId { get; set; }
    public string strPurpose { get; set; } = string.Empty;
    public bool ysnActive { get; set; } = true;
}
